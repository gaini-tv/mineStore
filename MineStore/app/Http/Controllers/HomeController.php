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
        // Récupérer les 3 derniers produits ajoutés
        $nouveautes = Produit::where('actif', true)
            ->orderBy('date_creation', 'desc')
            ->limit(3)
            ->get();

        // Récupérer les jeux (produits contenant "Minecraft" mais pas "Pop")
        $jeux = Produit::where('actif', true)
            ->where('nom', 'like', '%Minecraft%')
            ->where('nom', 'not like', '%Pop%')
            ->orderBy('nom')
            ->get();

        return view('welcome', [
            'nouveautes' => $nouveautes,
            'jeux' => $jeux,
        ]);
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
