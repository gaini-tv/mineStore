<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use Illuminate\Http\Request;

class ProduitController extends Controller
{
    /**
     * Affiche la liste des produits.
     */
    public function index()
    {
        $produits = Produit::where('actif', true)
            ->orderBy('nom')
            ->get();

        return view('produits.index', [
            'produits' => $produits,
        ]);
    }
}
