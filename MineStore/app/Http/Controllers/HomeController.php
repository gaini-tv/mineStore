<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Page d'accueil.
     */
    public function index()
    {
        return view('welcome');
    }

    /**
     * Recherche dans la table produits (nom et description).
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        $produits = collect();

        if (strlen($query) >= 2) {
            $produits = Produit::query()
                ->where('nom', 'like', '%' . $query . '%')
                ->orWhere('description', 'like', '%' . $query . '%')
                ->orderBy('nom')
                ->get();
        }

        return view('search.produits', [
            'produits' => $produits,
            'query'    => $query,
        ]);
    }
}
