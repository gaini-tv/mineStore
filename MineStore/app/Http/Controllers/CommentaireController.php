<?php

namespace App\Http\Controllers;

use App\Models\Commentaire;
use App\Models\Produit;
use App\Models\BannedWord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentaireController extends Controller
{
    /**
     * Stocke un nouveau commentaire.
     */
    public function store(Request $request, $produitId)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Vous devez être connecté pour laisser un commentaire.');
        }

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

        $userId = Auth::id();

        $contenuFiltre = BannedWord::filterText($request->contenu);

        $commentaire = Commentaire::create([
            'contenu' => $contenuFiltre,
            'note' => $request->note,
            'date_' => now(),
            'statut' => 'approuvé',
            'user_id' => $userId,
            'produit_id' => $produit->id_produit,
        ]);

        return redirect()->route('produits.show', $produitId)
            ->with('success', 'Votre commentaire a été ajouté avec succès !');
    }

    public function destroy(Commentaire $commentaire)
    {
        $user = Auth::user();

        if (!$user) {
            abort(403);
        }

        $isOwner = $commentaire->user_id === $user->id;
        $isAdmin = $user->role === 'admin';

        if (!$isOwner && !$isAdmin) {
            abort(403);
        }

        $produitId = $commentaire->produit_id;

        $commentaire->delete();

        return redirect()->route('produits.show', $produitId)->with('success', 'Commentaire supprimé avec succès.');
    }
}
