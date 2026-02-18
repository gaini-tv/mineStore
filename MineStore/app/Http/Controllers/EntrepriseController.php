<?php

namespace App\Http\Controllers;

use App\Models\Entreprise;
use App\Models\Produit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

    public function index()
    {
        $user = Auth::user();
        if (!$user || !$user->entreprise || $user->role === 'admin' || $user->role === 'user') {
            abort(403);
        }

        $entreprise = $user->entreprise;

        $produitsQuery = Produit::where('entreprise_id', $entreprise->id_entreprise);
        $produitsEnLigne = (clone $produitsQuery)->where('actif', true)->count();

        $ventes = DB::table('ligne_commandes')
            ->join('produits', 'ligne_commandes.produit_id', '=', 'produits.id_produit')
            ->where('produits.entreprise_id', $entreprise->id_entreprise)
            ->selectRaw('SUM(ligne_commandes.quantité) as total_quantite, SUM(ligne_commandes.prix_TTC - ligne_commandes.prix_HT) as total_benefice')
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

        $nombreArticles = 0;

        return view('entreprise.index', [
            'entreprise' => $entreprise,
            'produitsEnLigne' => $produitsEnLigne,
            'totalProduitsVendus' => $totalProduitsVendus,
            'meilleureVente' => $meilleureVente,
            'membresParRole' => $membresParRole,
            'nombreArticles' => $nombreArticles,
            'benefices' => $benefices,
        ]);
    }
}
