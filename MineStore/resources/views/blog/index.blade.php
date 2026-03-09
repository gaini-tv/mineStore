@extends('layouts.app')

@section('title', 'Blog')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/modal-form.css') }}">
    <style>
        .blog-tab-link { color: #1b1b18 !important; }
        .blog-tab-link:hover { color: #5baa47 !important; }
        .blog-tab-active { background: #5baa47 !important; color: white !important; }
        .blog-tab-active:hover { color: white !important; }
    </style>
@endpush

@section('content')
    {{-- Bannière --}}
    <div class="w-full mb-8 relative">
        <img src="{{ asset('images/banierP.png') }}" alt="Bannière blog" class="w-full h-auto">
        <h1 class="absolute inset-0 flex items-center justify-center font-bold text-white" style="font-family: 'Minecrafter Alt', sans-serif; font-size: 6rem; text-shadow: 3px 3px 6px rgba(0, 0, 0, 0.9);">
            Blog
        </h1>
    </div>

    <div class="container mx-auto px-4 py-8" style="padding-top: 120px;">
        {{-- Onglets par catégorie --}}
        <div class="blog-category-tabs" style="display: flex; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 2rem; justify-content: center;">
            <a href="{{ route('blog.index') }}"
               class="blog-tab-link {{ !$categorieId ? 'blog-tab-active' : '' }}"
               style="padding: 0.5rem 1rem; border-radius: 4px; font-family: 'Minecrafter Alt', sans-serif; text-decoration: none; color: #1b1b18; background: #e5e7eb; transition: color 0.2s, background 0.2s;">
                Tous
            </a>
            @foreach($categories as $cat)
                <a href="{{ route('blog.index', ['categorie' => $cat->id_categorie]) }}"
                   class="blog-tab-link {{ $categorieId == $cat->id_categorie ? 'blog-tab-active' : '' }}"
                   style="padding: 0.5rem 1rem; border-radius: 4px; font-family: 'Minecrafter Alt', sans-serif; text-decoration: none; color: #1b1b18; background: #e5e7eb; transition: color 0.2s, background 0.2s;">
                    {{ $cat->nom }} ({{ $cat->produits_count ?? 0 }})
                </a>
            @endforeach
        </div>

        {{-- Bouton admin : ajouter un article --}}
        @if($isAdmin ?? false)
            <div class="mb-6 flex justify-end">
                <button type="button" id="open-add-article-btn"
                        class="px-4 py-2 rounded"
                        style="background-color: #5baa47; color: white; font-family: 'Minecrafter Alt', sans-serif; border: 2px solid #3f7c33; cursor: pointer;">
                    + Nouvel article
                </button>
            </div>
        @endif

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        {{-- Liste des articles --}}
        @if($articles->count() > 0)
            <div class="blog-grid-articles" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; width: 100%;">
                @foreach($articles as $article)
                    <a href="{{ route('blog.show', $article) }}" class="blog-card-link" style="display: block; background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden; transition: box-shadow 0.2s; width: 100%; min-width: 0; text-decoration: none;">
                        <div style="height: 192px; background: #f3f4f6; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                            @if($article->produit && $article->produit->image)
                                <img src="{{ asset($article->produit->image) }}" alt="{{ $article->produit->nom }}" style="width: 100%; height: 100%; object-fit: contain;">
                            @else
                                <img src="{{ asset('images/placeholder-product.png') }}" alt="" style="width: 100%; height: 100%; object-fit: contain;">
                            @endif
                        </div>
                        <div style="padding: 1rem;">
                            <h2 class="blog-card-title" style="font-family: 'Minecrafter Alt', sans-serif; font-size: 1.125rem; font-weight: bold; color: #1b1b18; margin-bottom: 0.25rem;">
                                {{ $article->nom }}
                            </h2>
                            <p class="blog-card-desc" style="font-size: 0.875rem; color: #1b1b18; margin-bottom: 0.5rem;">
                                {{ Str::limit(strip_tags($article->description), 80) }}
                            </p>
                            <p class="blog-card-meta" style="font-size: 0.75rem; color: #1b1b18; font-family: 'Minecrafter Alt', sans-serif; margin: 0;">
                                {{ $article->produit?->nom ?? 'Produit' }} · {{ $article->created_at->format('d/m/Y') }}
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>
            <style>
                @media (max-width: 768px) {
                    .blog-grid-articles { grid-template-columns: 1fr !important; }
                }
                .blog-card-link:hover .blog-card-title,
                .blog-card-link:hover .blog-card-desc,
                .blog-card-link:hover .blog-card-meta {
                    color: #5baa47 !important;
                }
            </style>

            <div style="margin-top: 2rem;">
                {{ $articles->links() }}
            </div>
        @else
            <div class="text-center py-16 bg-white rounded-lg shadow">
                <p class="text-[#706f6c] text-lg" style="font-family: 'Minecrafter Alt', sans-serif;">
                    Aucun article pour le moment.
                    @if($isAdmin ?? false)
                        Cliquez sur "Nouvel article" pour en créer un.
                    @endif
                </p>
            </div>
        @endif
    </div>

    {{-- Modale ajout article (admin) --}}
    @if($isAdmin ?? false)
        <div id="add-article-modal" class="modal-form-backdrop hidden">
            <div class="modal-form-container">
                <div class="modal-form-header">
                    <h2 class="modal-form-title">Nouvel article</h2>
                    <button type="button" id="close-add-article-btn" class="modal-form-close-button">
                        <img src="{{ asset('images/cross.png') }}" alt="Fermer" style="width: 24px; height: 24px;">
                    </button>
                </div>
                <form action="{{ route('blog.store') }}" method="POST">
                    @csrf
                    <div style="padding: 20px;">
                        <div class="mb-4">
                            <label class="modal-form-label">Titre</label>
                            <div class="modal-form-field-wrapper">
                                <input type="text" name="titre" class="modal-form-input" required value="{{ old('titre') }}">
                            </div>
                            @error('titre')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label class="modal-form-label">Produit lié</label>
                            <div class="modal-form-field-wrapper">
                                <select name="produit_id" class="modal-form-select" required>
                                    <option value="">Choisir un produit</option>
                                    @foreach(\App\Models\Produit::where('actif', true)->orderBy('nom')->get() as $p)
                                        <option value="{{ $p->id_produit }}" {{ old('produit_id') == $p->id_produit ? 'selected' : '' }}>
                                            {{ $p->nom }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('produit_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label class="modal-form-label">Contenu</label>
                            <div class="modal-form-field-wrapper">
                                <textarea name="contenu" rows="8" class="modal-form-textarea" required>{{ old('contenu') }}</textarea>
                            </div>
                            @error('contenu')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-form-footer">
                        <button type="button" id="cancel-add-article-btn" class="px-4 py-2" style="background-color: #e5e7eb; color: #1f2933; font-family: 'Minecrafter Alt', sans-serif; border: 2px solid #9ca3af; cursor: pointer;">
                            Annuler
                        </button>
                        <button type="submit" class="px-4 py-2" style="background-color: #5baa47; color: #ffffff; font-family: 'Minecrafter Alt', sans-serif; border: 2px solid #3f7c33; cursor: pointer;">
                            Publier
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const openBtn = document.getElementById('open-add-article-btn');
            const modal = document.getElementById('add-article-modal');
            const closeBtn = document.getElementById('close-add-article-btn');
            const cancelBtn = document.getElementById('cancel-add-article-btn');

            if (openBtn && modal) {
                openBtn.addEventListener('click', () => modal.classList.remove('hidden'));
            }
            if (closeBtn && modal) {
                closeBtn.addEventListener('click', () => modal.classList.add('hidden'));
            }
            if (cancelBtn && modal) {
                cancelBtn.addEventListener('click', () => modal.classList.add('hidden'));
            }
            if (modal) {
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) modal.classList.add('hidden');
                });
            }
        });
    </script>
    @endpush
@endsection
