<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Entreprise;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    protected function ensureAdmin(): void
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403);
        }
    }

    public function index(Request $request): View
    {
        $this->ensureAdmin();

        $usersQuery = User::query();

        if ($search = $request->query('search')) {
            $usersQuery->where(function ($q) use ($search) {
                $q->where('prenom', 'like', '%' . $search . '%')
                    ->orWhere('nom', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        if ($filterRole = $request->query('filter_role')) {
            $usersQuery->where('role', 'like', '%' . $filterRole . '%');
        }
        
        $sort = $request->query('sort', 'name');
        $direction = $request->query('direction', 'asc') === 'desc' ? 'desc' : 'asc';

        switch ($sort) {
            case 'email':
                $usersQuery->orderBy('email', $direction);
                break;
            case 'role':
                $usersQuery->orderBy('role', $direction);
                break;
            case 'name':
            default:
                $usersQuery->orderBy('nom', $direction)->orderBy('prenom', $direction);
                break;
        }

        $users = $usersQuery->get();
        $categories = Categorie::orderBy('nom')->get();
        $entreprises = Entreprise::where('statut', 'active')->orderBy('nom')->get();
        $demandesCreation = Entreprise::where('statut', 'pending')->orderBy('nom')->get();
        $demandesSuppression = Entreprise::where('statut', 'deletion_requested')->orderBy('nom')->get();

        return view('admin.index', [
            'users' => $users,
            'categories' => $categories,
            'entreprises' => $entreprises,
            'demandesCreation' => $demandesCreation,
            'demandesSuppression' => $demandesSuppression,
        ]);
    }

    public function updateUserRole(Request $request, User $user): RedirectResponse
    {
        $this->ensureAdmin();

        $validated = $request->validate([
            'role' => 'required|in:user,admin,owner,manager,product_manager,stock_manager,editor',
        ]);

        if ($user->email === 'minestore-Admin@gmail.com') {
            return back();
        }

        $user->role = $validated['role'];
        $user->save();

        return back()->with('success', 'Rôle mis à jour avec succès.');
    }

    public function updateUser(Request $request, User $user): RedirectResponse
    {
        $this->ensureAdmin();

        if ($user->email === 'minestore-Admin@gmail.com') {
            return back();
        }

        $validated = $request->validate([
            'prenom' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:user,admin',
        ]);

        $user->update($validated);

        return back()->with('success', 'Utilisateur mis à jour avec succès.');
    }

    public function destroyUser(User $user): RedirectResponse
    {
        $this->ensureAdmin();

        if ($user->email === 'minestore-Admin@gmail.com') {
            return back();
        }

        $user->delete();

        return back()->with('success', 'Utilisateur supprimé avec succès.');
    }

    public function approveEntreprise(Entreprise $entreprise): RedirectResponse
    {
        $this->ensureAdmin();

        $entreprise->statut = 'active';
        $entreprise->save();

        if ($entreprise->user_id) {
            $user = User::find($entreprise->user_id);
            if ($user) {
                $user->role = 'owner';
                $user->entreprise_id = $entreprise->id_entreprise;
                $user->save();
            }
        }

        return back()->with('success', 'Entreprise approuvée et propriétaire défini.');
    }

    public function refuseEntreprise(Entreprise $entreprise): RedirectResponse
    {
        $this->ensureAdmin();

        $entreprise->statut = 'refused';
        $entreprise->save();

        return back()->with('success', 'Demande de création d’entreprise refusée.');
    }

    public function approveEntrepriseDeletion(Entreprise $entreprise): RedirectResponse
    {
        $this->ensureAdmin();

        if ($entreprise->statut === 'deletion_requested') {
            $membres = User::where('entreprise_id', $entreprise->id_entreprise)->get();

            foreach ($membres as $membre) {
                if ($membre->role !== 'admin') {
                    $membre->role = 'user';
                }
                $membre->entreprise_id = null;
                $membre->save();
            }

            $entreprise->delete();
            return back()->with('success', 'Entreprise supprimée.');
        }

        return back()->with('success', 'Aucune suppression effectuée.');
    }

    public function cancelEntrepriseDeletion(Entreprise $entreprise): RedirectResponse
    {
        $this->ensureAdmin();

        if ($entreprise->statut === 'deletion_requested') {
            $entreprise->statut = 'active';
            $entreprise->save();
        }

        return back()->with('success', 'Demande de suppression annulée.');
    }

    public function storeCategory(Request $request): RedirectResponse
    {
        $this->ensureAdmin();

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        if (mb_strtolower(trim($validated['nom'])) === mb_strtolower('Non catégorisé')) {
            return back();
        }

        Categorie::create($validated);

        return back()->with('success', 'Catégorie créée avec succès.');
    }

    public function updateCategory(Request $request, Categorie $categorie): RedirectResponse
    {
        $this->ensureAdmin();

        if (mb_strtolower(trim($categorie->nom)) === mb_strtolower('Non catégorisé')) {
            return back();
        }

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $categorie->update($validated);

        return back()->with('success', 'Catégorie mise à jour avec succès.');
    }

    public function destroyCategory(Categorie $categorie): RedirectResponse
    {
        $this->ensureAdmin();

        if (mb_strtolower(trim($categorie->nom)) === mb_strtolower('Non catégorisé')) {
            return back();
        }

        $uncategorized = Categorie::whereRaw('LOWER(nom) = ?', [mb_strtolower('Non catégorisé')])->first();

        if ($uncategorized) {
            $produits = $categorie->produits()->get();

            foreach ($produits as $produit) {
                if (!$produit->categories()->where('categories.id_categorie', $uncategorized->id_categorie)->exists()) {
                    $produit->categories()->attach($uncategorized->id_categorie);
                }
            }

            $categorie->produits()->detach();
        }

        $categorie->delete();

        return back()->with('success', 'Catégorie supprimée avec succès.');
    }
}
