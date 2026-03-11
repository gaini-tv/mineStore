<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Commentaire;
use App\Models\Categorie;
use App\Models\Produit;
use App\Models\BannedWord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $categorieId = $request->get('categorie');

        $categories = Categorie::whereHas('produits', function ($q) {
            $q->whereHas('articles')->where('actif', true);
        })->withCount(['produits' => function ($q) {
            $q->whereHas('articles')->where('actif', true);
        }])->orderBy('nom')->get();

        $query = Article::with(['produits.categories'])
            ->whereHas('produits', fn ($q) => $q->where('actif', true));

        if ($categorieId) {
            $query->whereHas('produits.categories', fn ($q) => $q->where('categories.id_categorie', $categorieId));
        }

        $articles = $query->latest()->paginate(9);

        return view('blog.index', [
            'articles' => $articles,
            'categories' => $categories,
            'categorieId' => $categorieId,
            'isAdmin' => Auth::check() && Auth::user()->role === 'admin',
        ]);
    }

    public function show(Article $article)
    {
        $article->load(['produits.categories', 'produits.commentaires.user']);

        return view('blog.show', [
            'article' => $article,
            'isAdmin' => Auth::check() && Auth::user()->role === 'admin',
        ]);
    }

    public function store(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'contenu' => 'required|string',
            'produit_id' => 'required|exists:produits,id_produit',
        ]);

        $article = Article::create([
            'nom' => $validated['titre'],
            'description' => $validated['contenu'],
        ]);

        $article->produits()->attach($validated['produit_id']);

        return redirect()->route('blog.index')->with('success', 'Article publié.');
    }

    public function storeComment(Request $request, Article $article)
    {
        $request->validate([
            'contenu' => 'required|string|max:2000',
        ]);

        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Connectez-vous pour commenter.');
        }

        $produit = $article->produit;
        if (!$produit) {
            return back()->with('error', 'Impossible de commenter : aucun produit associé.');
        }

        $contenuFiltre = BannedWord::filterText($request->contenu);

        Commentaire::create([
            'produit_id' => $produit->id_produit,
            'user_id' => Auth::id(),
            'contenu' => $contenuFiltre,
            'date_' => now(),
            'statut' => 'approuvé',
            'note' => 5, // Note par défaut pour compatibilité
        ]);

        return redirect()->route('blog.show', $article)->with('success', 'Commentaire ajouté.');
    }

    public function destroyComment(Commentaire $commentaire)
    {
        if (!Auth::check()) {
            abort(403);
        }
        if (Auth::user()->role !== 'admin' && Auth::id() !== $commentaire->user_id) {
            abort(403);
        }

        $commentaire->delete();

        return back()->with('success', 'Commentaire supprimé.');
    }

    public function destroy(Article $article)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403);
        }

        $article->produits()->detach();
        $article->delete();

        return back()->with('success', 'Article supprimé.');
    }
}
