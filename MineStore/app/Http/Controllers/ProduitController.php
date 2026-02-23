<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Produit;
use App\Models\Commentaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProduitController extends Controller
{
    public function index(Request $request)
    {
        $query = Produit::where('actif', true);

        if ($request->filled('search')) {
            $query->where('nom', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('categorie_id')) {
            $categorieId = $request->categorie_id;

            $query->whereHas('categories', function ($q) use ($categorieId) {
                $q->where('categories.id_categorie', $categorieId);
            });
        }

        if ($request->filled('prix_min')) {
            $query->where('prix', '>=', $request->prix_min);
        }

        if ($request->filled('prix_max')) {
            $query->where('prix', '<=', $request->prix_max);
        }

        if ($request->filled('stock')) {
            switch ($request->stock) {
                case 'en_stock':
                    $query->where('stock', '>', 10);
                    break;
                case 'stock_faible':
                    $query->whereBetween('stock', [1, 10]);
                    break;
                case 'rupture':
                    $query->where('stock', '=', 0);
                    break;
            }
        }

        if ($request->filled('pegi')) {
            $query->where('pegi', $request->pegi);
        }

        $sortBy = $request->get('sort', 'nom');
        $sortOrder = $request->get('order', 'asc');

        switch ($sortBy) {
            case 'prix':
                $query->orderBy('prix', $sortOrder);
                break;
            case 'date':
                $query->orderBy('date_creation', $sortOrder);
                break;
            case 'stock':
                $query->orderBy('stock', $sortOrder);
                break;
            default:
                $query->orderBy('nom', $sortOrder);
        }

        $produits = $query->get();

        $categories = Categorie::withCount(['produits' => function ($q) {
            $q->where('actif', true);
        }])->orderBy('nom')->get();

        $canAddProduct = false;

        if (Auth::check()) {
            $user = Auth::user();

            if ($user->role === 'admin') {
                $canAddProduct = true;
            } elseif (in_array($user->role, ['owner', 'manager', 'product_manager'], true) && $user->entreprise_id) {
                $canAddProduct = true;
            }
        }

        $totalProduitsActifs = Produit::where('actif', true)->count();

        return view('produits.index', [
            'produits' => $produits,
            'categories' => $categories,
            'canAddProduct' => $canAddProduct,
            'totalProduitsActifs' => $totalProduitsActifs,
        ]);
    }

    public function show($id)
    {
        $produit = Produit::with('categories')
            ->where('id_produit', $id)
            ->where('actif', true)
            ->firstOrFail();

        $commentaires = Commentaire::where('produit_id', $produit->id_produit)
            ->where('statut', 'approuvé')
            ->with('user')
            ->orderBy('date_', 'desc')
            ->get();

        $produitsSuggere = Produit::where('actif', true)
            ->where('id_produit', '!=', $id)
            ->where('nom', 'like', '%Pop%')
            ->inRandomOrder()
            ->limit(4)
            ->get();

        $categories = Categorie::orderBy('nom')->get();

        $canManageProduct = false;

        if (Auth::check()) {
            $user = Auth::user();
            $allowedRoles = ['admin', 'owner', 'manager', 'product_manager'];

            if (in_array($user->role, $allowedRoles, true)) {
                if ($user->role === 'admin') {
                    $canManageProduct = true;
                } else {
                    if ($user->entreprise_id && $produit->entreprise_id && $user->entreprise_id === $produit->entreprise_id) {
                        $canManageProduct = true;
                    }
                }
            }
        }

        return view('produits.show', [
            'produit' => $produit,
            'commentaires' => $commentaires,
            'produitsSuggere' => $produitsSuggere,
            'categories' => $categories,
            'canManageProduct' => $canManageProduct,
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            abort(403);
        }

        $allowedRoles = ['admin', 'owner', 'manager', 'product_manager'];

        if (!in_array($user->role, $allowedRoles, true)) {
            abort(403);
        }

        $entrepriseId = null;

        if ($user->role !== 'admin') {
            if (!$user->entreprise_id) {
                return redirect()->route('produits.index')->withErrors([
                    'entreprise' => 'Vous devez être associé à une entreprise pour ajouter un produit.',
                ]);
            }

            $entrepriseId = $user->entreprise_id;
        }

        $data = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'prix' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'stock_low_threshold' => ['nullable', 'integer', 'min:1'],
            'reference' => ['required', 'string', 'max:255', 'unique:produits,reference'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'pegi' => ['nullable', 'string', 'max:255'],
            'infinite_stock' => ['nullable', 'boolean'],
            'rupture_marketing' => ['nullable', 'boolean'],
            'categorie_id' => ['required', 'exists:categories,id_categorie'],
        ]);

        $imagePath = 'images/logo.png';

        if ($request->hasFile('image')) {
            $imageFile = $request->file('image');
            $filename = uniqid('prod_') . '.' . $imageFile->getClientOriginalExtension();
            $destination = public_path('images/produits');

            if (!is_dir($destination)) {
                mkdir($destination, 0755, true);
            }

            $imageFile->move($destination, $filename);

            $imagePath = 'images/produits/' . $filename;
        }

        $produit = new Produit();
        $produit->nom = $data['nom'];
        $produit->description = $data['description'] ?? null;
        $produit->prix = $data['prix'];
        $produit->stock = $data['stock'];
        $produit->stock_low_threshold = $data['stock_low_threshold'] ?? 100;
        $produit->infinite_stock = $request->boolean('infinite_stock');
        $produit->rupture_marketing = $request->boolean('rupture_marketing');
        $produit->reference = $data['reference'];
        $produit->image = $imagePath;
        $produit->pegi = $data['pegi'] ?: null;
        $produit->actif = true;
        $produit->date_creation = now();
        $produit->entreprise_id = $entrepriseId;
        $produit->save();

        $produit->categories()->attach($data['categorie_id']);

        return redirect()->route('produits.index')->with('success', 'Produit ajouté avec succès.');
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();

        if (!$user) {
            abort(403);
        }

        $allowedRoles = ['admin', 'owner', 'manager', 'product_manager'];

        if (!in_array($user->role, $allowedRoles, true)) {
            abort(403);
        }

        $produit = Produit::with('categories')->where('id_produit', $id)->firstOrFail();

        if ($user->role !== 'admin') {
            if (!$user->entreprise_id || !$produit->entreprise_id || $user->entreprise_id !== $produit->entreprise_id) {
                abort(403);
            }
        }

        $data = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'prix' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'stock_low_threshold' => ['nullable', 'integer', 'min:1'],
            'reference' => ['required', 'string', 'max:255', 'unique:produits,reference,' . $produit->id_produit . ',id_produit'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'pegi' => ['nullable', 'string', 'max:255'],
            'infinite_stock' => ['nullable', 'boolean'],
            'rupture_marketing' => ['nullable', 'boolean'],
            'categorie_id' => ['required', 'exists:categories,id_categorie'],
        ]);

        $imagePath = $produit->image ?? 'images/logo.png';

        if ($request->hasFile('image')) {
            $protectedPrefix = 'images/produits/defaultdev/';

            if (
                $produit->image &&
                $produit->image !== 'images/logo.png' &&
                strpos($produit->image, $protectedPrefix) !== 0
            ) {
                $existingPath = public_path($produit->image);
                if (is_file($existingPath)) {
                    @unlink($existingPath);
                }
            }

            $imageFile = $request->file('image');
            $filename = uniqid('prod_') . '.' . $imageFile->getClientOriginalExtension();
            $destination = public_path('images/produits');

            if (!is_dir($destination)) {
                mkdir($destination, 0755, true);
            }

            $imageFile->move($destination, $filename);

            $imagePath = 'images/produits/' . $filename;
        }

        $produit->nom = $data['nom'];
        $produit->description = $data['description'] ?? null;
        $produit->prix = $data['prix'];
        $produit->stock = $data['stock'];
        $produit->stock_low_threshold = $data['stock_low_threshold'] ?? 100;
        $produit->infinite_stock = $request->boolean('infinite_stock');
        $produit->rupture_marketing = $request->boolean('rupture_marketing');
        $produit->reference = $data['reference'];
        $produit->image = $imagePath;
        $produit->pegi = $data['pegi'] ?: null;
        $produit->save();

        $produit->categories()->sync([$data['categorie_id']]);

        return redirect()->route('produits.show', $produit->id_produit)->with('success', 'Produit mis à jour avec succès.');
    }

    public function destroy($id)
    {
        $user = Auth::user();

        if (!$user) {
            abort(403);
        }

        $allowedRoles = ['admin', 'owner', 'manager', 'product_manager'];

        if (!in_array($user->role, $allowedRoles, true)) {
            abort(403);
        }

        $produit = Produit::where('id_produit', $id)->firstOrFail();

        if ($user->role !== 'admin') {
            if (!$user->entreprise_id || !$produit->entreprise_id || $user->entreprise_id !== $produit->entreprise_id) {
                abort(403);
            }
        }

        $protectedPrefix = 'images/produits/defaultdev/';

        if (
            $produit->image &&
            $produit->image !== 'images/logo.png' &&
            strpos($produit->image, $protectedPrefix) !== 0
        ) {
            $existingPath = public_path($produit->image);
            if (is_file($existingPath)) {
                @unlink($existingPath);
            }
        }

        $produit->delete();

        return redirect()->route('produits.index')->with('success', 'Produit supprimé avec succès.');
    }

    public function updateStockSettings(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user) {
            abort(403);
        }
        $allowedRoles = ['admin', 'owner', 'manager', 'stock_manager'];
        if (!in_array($user->role, $allowedRoles, true)) {
            abort(403);
        }

        $produit = Produit::where('id_produit', $id)->firstOrFail();
        if ($user->role !== 'admin') {
            if (!$user->entreprise_id || !$produit->entreprise_id || $user->entreprise_id !== $produit->entreprise_id) {
                abort(403);
            }
        }

        $data = $request->validate([
            'stock' => ['nullable', 'integer', 'min:0'],
            'stock_low_threshold' => ['nullable', 'integer', 'min:1'],
            'infinite_stock' => ['nullable', 'boolean'],
            'rupture_marketing' => ['nullable', 'boolean'],
        ]);

        if (array_key_exists('stock', $data)) {
            $produit->stock = $data['stock'];
        }
        if (array_key_exists('stock_low_threshold', $data)) {
            $produit->stock_low_threshold = $data['stock_low_threshold'];
        }
        if (array_key_exists('infinite_stock', $data)) {
            $produit->infinite_stock = $request->boolean('infinite_stock');
        }
        if (array_key_exists('rupture_marketing', $data)) {
            $produit->rupture_marketing = $request->boolean('rupture_marketing');
        }

        $produit->save();

        return response()->json([
            'success' => true,
            'id' => $produit->id_produit,
            'stock' => $produit->stock,
            'stock_low_threshold' => $produit->stock_low_threshold,
            'infinite_stock' => $produit->infinite_stock,
            'rupture_marketing' => $produit->rupture_marketing,
        ]);
    }
}
