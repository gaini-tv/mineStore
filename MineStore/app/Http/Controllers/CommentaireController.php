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
        ], [
            'note.required' => 'Veuillez sélectionner une note en cliquant sur les étoiles.',
            'note.min' => 'La note doit être au moins 1.',
            'note.max' => 'La note ne peut pas dépasser 5.',
            'contenu.required' => 'Veuillez écrire un commentaire.',
        ]);

        $produit = Produit::where('id_produit', $produitId)
            ->where('actif', true)
            ->firstOrFail();

        // Utiliser l'utilisateur connecté ou créer un utilisateur anonyme
        $userId = Auth::id();
        
        // Si l'utilisateur n'est pas connecté, utiliser l'ID 1 ou créer un utilisateur par défaut
        if (!$userId) {
            $userId = 1; // Utilisateur par défaut
        }

        $commentaire = Commentaire::create([
            'contenu' => $request->contenu,
            'note' => $request->note,
            'date_' => now(),
            'statut' => 'approuvé',
            'user_id' => $userId,
            'produit_id' => $produit->id_produit,
        ]);

        return redirect()->route('produits.show', $produitId)
            ->with('success', 'Votre commentaire a été ajouté avec succès !');
    }
}
