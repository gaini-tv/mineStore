<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use Illuminate\Http\Request;

class ProfilController extends Controller
{
    /**
     * Affiche la page de profil avec les produits filtrés
     */
    public function index(Request $request)
    {
        $query = Produit::where('actif', true);

        // Filtre par recherche de nom
        if ($request->filled('search')) {
            $query->where('nom', 'like', '%' . $request->search . '%');
        }

        // Filtre par prix minimum
        if ($request->filled('prix_min')) {
            $query->where('prix', '>=', $request->prix_min);
        }

        // Filtre par prix maximum
        if ($request->filled('prix_max')) {
            $query->where('prix', '<=', $request->prix_max);
        }

        // Filtre par stock
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

        // Filtre par PEGI
        if ($request->filled('pegi')) {
            $query->where('pegi', $request->pegi);
        }

        // Tri
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

        // Récupérer les valeurs min/max pour les filtres
        $prixMin = Produit::where('actif', true)->min('prix') ?? 0;
        $prixMax = Produit::where('actif', true)->max('prix') ?? 1000;

        return view('profil.index', [
            'produits' => $produits,
            'prixMin' => $prixMin,
            'prixMax' => $prixMax,
            'filters' => $request->all(),
        ]);
    }
}
