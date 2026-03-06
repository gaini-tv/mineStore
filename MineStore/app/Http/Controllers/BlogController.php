<?php

namespace App\Http\Controllers;

use App\Models\BlogArticle;
use App\Models\BlogCommentaire;
use App\Models\Categorie;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $categorieId = $request->get('categorie');

        $categories = Categorie::whereHas('produits', function ($q) {
            $q->whereHas('blogArticles')->where('actif', true);
        })->withCount(['produits' => function ($q) {
            $q->whereHas('blogArticles')->where('actif', true);
        }])->orderBy('nom')->get();

        $query = BlogArticle::with(['produit.categories', 'user'])
            ->whereHas('produit', fn ($q) => $q->where('actif', true));

        if ($categorieId) {
            $query->whereHas('produit.categories', fn ($q) => $q->where('categories.id_categorie', $categorieId));
        }

        $articles = $query->latest()->paginate(9);

        return view('blog.index', [
            'articles' => $articles,
            'categories' => $categories,
            'categorieId' => $categorieId,
            'isAdmin' => Auth::check() && Auth::user()->role === 'admin',
        ]);
    }

    public function show(BlogArticle $article)
    {
        $article->load(['produit.categories', 'user', 'commentaires.user']);

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

        BlogArticle::create([
            'titre' => $validated['titre'],
            'contenu' => $validated['contenu'],
            'produit_id' => $validated['produit_id'],
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('blog.index')->with('success', 'Article publié.');
    }

    public function storeComment(Request $request, BlogArticle $article)
    {
        $request->validate([
            'contenu' => 'required|string|max:2000',
        ]);

        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Connectez-vous pour commenter.');
        }

        BlogCommentaire::create([
            'blog_article_id' => $article->id_blog_article,
            'user_id' => Auth::id(),
            'contenu' => $request->contenu,
        ]);

        return redirect()->route('blog.show', $article)->with('success', 'Commentaire ajouté.');
    }

    public function destroyComment(BlogCommentaire $commentaire)
    {
        if (!Auth::check()) {
            abort(403);
        }
        if (Auth::user()->role !== 'admin' && Auth::id() !== $commentaire->user_id) {
            abort(403);
        }

        $article = $commentaire->blogArticle;
        $commentaire->delete();

        return redirect()->route('blog.show', $article)->with('success', 'Commentaire supprimé.');
    }
}
