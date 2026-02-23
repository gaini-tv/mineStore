<?php

namespace App\Http\Controllers;

use App\Models\Entreprise;
use App\Models\Categorie;
use App\Models\Produit;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class EntrepriseController extends Controller
{
    public function requestDeletion(): \Illuminate\Http\RedirectResponse
    {
        $user = Auth::user();
        if (!$user || !$user->entreprise || $user->role !== 'owner' || $user->entreprise->user_id !== $user->id) {
            abort(403);
        }

        $entreprise = $user->entreprise;
        if ($entreprise->statut !== 'active') {
            return redirect()->route('entreprise.index')->with('success', 'Une demande de suppression est déjà en cours.');
        }

        $token = Str::random(64);
        $entreprise->statut = 'deletion_pending_email';
        $entreprise->deletion_token = $token;
        $entreprise->deletion_token_expires_at = now()->addMinutes(15);
        $entreprise->save();

        $url = route('entreprise.confirmDeletion', ['token' => $token]);

        try {
            Mail::send([], [], function ($message) use ($user, $entreprise, $url) {
                $html = '<p>Bonjour ' . e($user->prenom) . ' ' . e($user->nom) . ',</p>' .
                    '<p>Vous avez demandé la suppression de votre entreprise <strong>' . e($entreprise->nom) . '</strong>.</p>' .
                    '<p>Cliquez sur le lien ci-dessous pour confirmer cette suppression (valide 15 minutes) :</p>' .
                    '<p><a href="' . e($url) . '">' . e($url) . '</a></p>' .
                    '<p>Si vous n\'êtes pas à l\'origine de cette demande, ignorez ce message.</p>';

                $message->to($user->email)
                    ->subject('Confirmation de suppression de votre entreprise')
                    ->html($html);
            });
        } catch (\Throwable $e) {
            Log::error('Erreur envoi mail suppression entreprise : ' . $e->getMessage());

            $entreprise->statut = 'active';
            $entreprise->deletion_token = null;
            $entreprise->deletion_token_expires_at = null;
            $entreprise->save();

            return redirect()
                ->route('entreprise.index')
                ->with('error', 'Erreur lors de l\'envoi de l\'email de confirmation : ' . $e->getMessage());
        }

        return redirect()->route('entreprise.index')->with('success', 'Un email de confirmation vous a été envoyé.');
    }

    public function confirmDeletion(string $token)
    {
        $entreprise = Entreprise::where('deletion_token', $token)
            ->where('statut', 'deletion_pending_email')
            ->where('deletion_token_expires_at', '>=', now())
            ->first();

        if (!$entreprise) {
            return redirect()->route('home')->with('error', 'Lien de confirmation invalide ou expiré.');
        }

        $entreprise->statut = 'deletion_requested';
        $entreprise->deletion_token = null;
        $entreprise->deletion_token_expires_at = null;
        $entreprise->save();

        return redirect()->route('entreprise.index')->with('success', 'Votre demande de suppression a été envoyée à l’administration.');
    }

    public function addMember(Request $request): \Illuminate\Http\RedirectResponse
    {
        $user = Auth::user();
        if (!$user || !$user->entreprise || $user->role !== 'owner' || $user->entreprise->user_id !== $user->id) {
            abort(403);
        }

        $validated = $request->validate([
            'email' => 'required|email',
            'role' => 'required|in:manager,product_manager,stock_manager,editor',
        ]);

        $membre = User::where('email', $validated['email'])->first();
        if (!$membre) {
            return redirect()->route('entreprise.index')->withErrors(['email' => 'Utilisateur introuvable.']);
        }

        $membre->role = $validated['role'];
        $membre->entreprise_id = $user->entreprise_id;
        $membre->save();

        return redirect()->route('entreprise.index')->with('success', 'Membre ajouté à l’entreprise.');
    }

    public function updateMemberRole(Request $request, User $member): \Illuminate\Http\RedirectResponse
    {
        $user = Auth::user();
        if (
            !$user ||
            !$user->entreprise ||
            $user->role !== 'owner' ||
            $user->entreprise->user_id !== $user->id ||
            $member->entreprise_id !== $user->entreprise_id
        ) {
            abort(403);
        }

        if ($member->id === $user->id) {
            return redirect()->route('entreprise.index')->withErrors([
                'role' => 'Vous ne pouvez pas modifier votre propre rôle.',
            ]);
        }

        $validated = $request->validate([
            'role' => 'required|in:manager,product_manager,stock_manager,editor',
        ]);

        $member->role = $validated['role'];
        $member->save();

        return redirect()->route('entreprise.index')->with('success', 'Rôle du membre mis à jour.');
    }

    public function removeMember(User $member): \Illuminate\Http\RedirectResponse
    {
        $user = Auth::user();
        if (
            !$user ||
            !$user->entreprise ||
            $user->role !== 'owner' ||
            $user->entreprise->user_id !== $user->id ||
            $member->entreprise_id !== $user->entreprise_id
        ) {
            abort(403);
        }

        if ($member->id === $user->id) {
            return redirect()->route('entreprise.index')->withErrors([
                'member' => 'Vous ne pouvez pas vous retirer vous-même de l’entreprise.',
            ]);
        }

        $member->role = 'user';
        $member->entreprise_id = null;
        $member->save();

        return redirect()->route('entreprise.index')->with('success', 'Membre retiré de l’entreprise.');
    }

    public function index()
    {
        $user = Auth::user();
        if (!$user || !$user->entreprise || $user->role === 'admin' || $user->role === 'user') {
            abort(403);
        }

        $entreprise = $user->entreprise;

        $produitsQuery = Produit::where('entreprise_id', $entreprise->id_entreprise);
        $produitsEnLigne = (clone $produitsQuery)->where('actif', true)->count();
        $produits = (clone $produitsQuery)->orderBy('nom')->get();

        $ventes = DB::table('ligne_commandes')
            ->join('produits', 'ligne_commandes.produit_id', '=', 'produits.id_produit')
            ->where('produits.entreprise_id', $entreprise->id_entreprise)
            ->selectRaw('
                SUM(ligne_commandes.quantité) as total_quantite,
                SUM(ligne_commandes.prix_HT * ligne_commandes.quantité) as total_benefice
            ')
            ->first();

        $totalProduitsVendus = $ventes?->total_quantite ?? 0;
        $benefices = $ventes?->total_benefice ?? 0;

        $meilleureVente = DB::table('ligne_commandes')
            ->join('produits', 'ligne_commandes.produit_id', '=', 'produits.id_produit')
            ->where('produits.entreprise_id', $entreprise->id_entreprise)
            ->selectRaw('produits.nom, SUM(ligne_commandes.quantité) as total_quantite')
            ->groupBy('produits.nom')
            ->orderByDesc('total_quantite')
            ->first();

        $roles = ['owner', 'manager', 'product_manager', 'stock_manager', 'editor'];
        $membresParRole = [];
        foreach ($roles as $role) {
            $membresParRole[$role] = User::where('entreprise_id', $entreprise->id_entreprise)->where('role', $role)->count();
        }

        $membres = User::where('entreprise_id', $entreprise->id_entreprise)
            ->orderBy('prenom')
            ->orderBy('nom')
            ->get();

        $articles = DB::table('articles')
            ->join('asso_produit_article', 'articles.id_article', '=', 'asso_produit_article.article_id')
            ->join('produits', 'asso_produit_article.produit_id', '=', 'produits.id_produit')
            ->where('produits.entreprise_id', $entreprise->id_entreprise)
            ->select('articles.*', 'produits.nom as produit_nom', 'produits.id_produit')
            ->orderBy('articles.nom')
            ->get();
        $nombreArticles = $articles->count();

        $userRole = $user->role;
        $canManageProducts = in_array($userRole, ['owner', 'manager', 'product_manager'], true);
        $canManageStocks = in_array($userRole, ['owner', 'manager', 'stock_manager'], true);
        $categories = Categorie::orderBy('nom')->get();

        return view('entreprise.index', [
            'entreprise' => $entreprise,
            'produitsEnLigne' => $produitsEnLigne,
            'produits' => $produits,
            'totalProduitsVendus' => $totalProduitsVendus,
            'meilleureVente' => $meilleureVente,
            'membresParRole' => $membresParRole,
            'nombreArticles' => $nombreArticles,
            'benefices' => $benefices,
            'articles' => $articles,
            'membres' => $membres,
            'canManageProducts' => $canManageProducts,
            'canManageStocks' => $canManageStocks,
            'categories' => $categories,
        ]);
    }

    public function storeArticle(Request $request): \Illuminate\Http\RedirectResponse
    {
        $user = Auth::user();
        if (!$user || !$user->entreprise || !in_array($user->role, ['owner','manager','editor'], true)) {
            abort(403);
        }

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'produit_id' => 'required|integer',
        ]);

        $produit = Produit::where('id_produit', $validated['produit_id'])
            ->where('entreprise_id', $user->entreprise_id)
            ->first();
        if (!$produit) {
            abort(403);
        }

        $articleId = DB::table('articles')->insertGetId([
            'nom' => $validated['nom'],
            'description' => $validated['description'] ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ], 'id_article');

        DB::table('asso_produit_article')->insert([
            'produit_id' => $produit->id_produit,
            'article_id' => $articleId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('entreprise.index')->with('success', 'Article ajouté.');
    }

    public function updateArticle(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $user = Auth::user();
        if (!$user || !$user->entreprise || !in_array($user->role, ['owner','manager','editor'], true)) {
            abort(403);
        }

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        DB::table('articles')->where('id_article', $id)->update([
            'nom' => $validated['nom'],
            'description' => $validated['description'] ?? null,
            'updated_at' => now(),
        ]);

        return redirect()->route('entreprise.index')->with('success', 'Article modifié.');
    }

    public function destroyArticle(int $id): \Illuminate\Http\RedirectResponse
    {
        $user = Auth::user();
        if (!$user || !$user->entreprise || !in_array($user->role, ['owner','manager','editor'], true)) {
            abort(403);
        }

        DB::table('articles')->where('id_article', $id)->delete();

        return redirect()->route('entreprise.index')->with('success', 'Article supprimé.');
    }
}
