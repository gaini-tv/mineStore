<?php

namespace App\Http\Controllers;

use App\Models\Commentaire;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentaireController extends Controller
{
    /**
     * Stocke un nouveau commentaire.
     */
    public function store(Request $request, $produitId)
    {
        $request->validate([
            'contenu' => 'required|string|max:1000',
            'note' => 'required|integer|min:1|max:5',
        ]);

        $produit = Produit::where('id_produit', $produitId)
            ->where('actif', true)
            ->firstOrFail();

        // Utiliser l'utilisateur connecté ou un utilisateur par défaut (pour les tests)
        $userId = Auth::id() ?? 1; // À adapter selon votre système d'authentification

        Commentaire::create([
            'contenu' => $request->contenu,
            'note' => $request->note,
            'date_' => now(),
            'statut' => 'approuvé', // ou 'en_attente' selon votre logique
            'user_id' => $userId,
            'produit_id' => $produit->id_produit,
        ]);

        return redirect()->route('produits.show', $produitId)
            ->with('success', 'Votre commentaire a été ajouté avec succès !');
    }
}
