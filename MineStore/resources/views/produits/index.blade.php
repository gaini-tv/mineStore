@extends('layouts.app')

@section('title', 'Nos produits')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/modal-form.css') }}">
@endpush

@section('content')
    {{-- Bannière avec texte centré --}}
    <div class="w-full mb-8 relative">
        <img src="{{ asset('images/banierP.png') }}" alt="Bannière produits" class="w-full h-auto">
        <h1 class="absolute inset-0 flex items-center justify-center font-bold text-white" style="font-family: 'Minecrafter Alt', sans-serif; font-size: 6rem; text-shadow: 3px 3px 6px rgba(0, 0, 0, 0.9);">
            Nos produits
        </h1>
    </div>
    
    <div class="container mx-auto px-4 py-8" style="padding-bottom: 5rem; ">
        <div class="ProductNav mb-6"
             style="display: grid;margin-bottom:1rem; grid-template-columns: repeat(3, minmax(0, 1fr)); grid-template-rows: auto auto; column-gap: 1.5rem; row-gap: 1rem; align-items: center;">
            <div class="searchProduct flex justify-center" style="grid-column: 1; grid-row: 1; width: 100%;">
                <form method="GET" action="{{ route('produits.index') }}" class="flex items-center gap-2" style="width: 100%;">
                    <div class="searchbar-container flex gap-2 p-2" style="width: 100%; background-image: url('{{ asset('images/searchbar.png') }}'); background-size: 100% 60%; background-position: center; background-repeat: no-repeat; border: none; border-radius: 0;">
                        <input type="text"
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Recherche..."
                               class="flex-1 px-3 py-2 border-0 bg-transparent text-white placeholder-[#706f6c] focus:outline-none text-sm"
                               style="font-family: 'Minecrafter Alt', sans-serif; border-radius: 0; border: none; color: white;">
                        <button type="submit"
                                class="p-2 pr-6 bg-transparent hover:opacity-80 transition-opacity flex items-center justify-center"
                                style="border-radius: 0; border: none; cursor: pointer;">
                            <img src="{{ asset('images/iconrecherce.png') }}" alt="Rechercher" class="h-6 w-6">
                        </button>
                    </div>
                </form>
            </div>

            <div class="FilterProduct flex items-center justify-start gap-4" style="grid-column: 2; grid-row: 1;">
                <form method="GET" action="{{ route('produits.index') }}">
                    <select name="categorie_id"
                            class="px-3 py-2 bg-white rounded border border-[#e3e3e0]"
                            style="font-family: 'Minecrafter Alt', sans-serif;"
                            onchange="this.form.submit()">
                        <option value="">Toutes les catégories</option>
                        @foreach(($categories ?? []) as $cat)
                            <option value="{{ $cat->id_categorie }}" @selected(request('categorie_id') == $cat->id_categorie)>{{ $cat->nom }}</option>
                        @endforeach
                    </select>
                </form>
                <button id="open-filter-btn"
                        class="px-4 py-2 bg-white rounded border border-[#e3e3e0] flex items-center gap-2 hover:opacity-80 transition-opacity"
                        style="font-family: 'Minecrafter Alt', sans-serif;">
                    <span>Filtre</span>
                    <img src="{{ asset('images/filtre.png') }}" alt="Filtrer" class="h-8 w-8">
                </button>
            </div>

            <div class="flex justify-end" style="grid-column: 3; grid-row: 1; justify-self: end;">
                @if($canAddProduct ?? false)
                    <div class="relative" style="display: inline-block; width: 300px; margin: 0;">
                        <img src="{{ asset('images/btn.png') }}" alt="" class="w-full h-auto block">
                        <button type="button"
                                id="open-add-product-btn"
                                class="absolute inset-0 w-full h-full flex items-center justify-center hover:opacity-90 transition-opacity duration-200"
                                style="background: transparent; border: none; cursor: pointer; padding: 0;">
                            <span class="text-white font-bold text-sm md:text-base" style="font-family: 'Minecrafter Alt', sans-serif; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8); font-size: 1rem;">
                                AJOUTER UN PRODUIT
                            </span>
                        </button>
                    </div>
                @endif
            </div>

            <div class="TextResult flex items-center gap-4 justify-center"
                 style="grid-column: 1 / span 3; grid-row: 2;">
                <p class="text-[#706f6c]" style="font-family: 'Minecrafter Alt', sans-serif;">
                    {{ $produits->count() }} produit(s) trouvé(s)
                </p>
            </div>
        </div>

        {{-- Popup de filtrage --}}
        <div id="filter-modal" class="hidden modal-form-backdrop">
            <div class="modal-form-container">
                <div class="modal-form-header">
                    <h2 class="modal-form-title">Filtrer les produits</h2>
                    <button id="close-filter-btn" class="modal-form-close-button hover:opacity-80 transition-opacity">
                        <img src="{{ asset('images/cross.png') }}" alt="Fermer" class="h-6 w-6">
                    </button>
                </div>
                
                <form method="GET" action="{{ route('produits.index') }}" class="space-y-4" style="display: flex; flex-direction: column; ">
                    {{-- Recherche par nom --}}
                    <div style="padding: 20px;">
                        <label class="modal-form-label">Rechercher un produit</label>
                        <div class="modal-form-field-wrapper">
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Nom du produit..."
                                   class="modal-form-input"
                                   style="border: none;">
                        </div>
                    </div>

                    {{-- Filtre par prix --}}
                    <div class="grid grid-cols-2 gap-4" style="padding: 20px;">
                        <div>
                            <label class="modal-form-label">Prix min (€)</label>
                            <div class="modal-form-field-wrapper">
                                <input type="number" 
                                       name="prix_min" 
                                       value="{{ request('prix_min') }}"
                                       min="0"
                                       step="0.01"
                                       placeholder="Min"
                                       class="modal-form-input"
                                       style="border: none;">
                            </div>
                        </div>
                        <div>
                            <label class="modal-form-label">Prix max (€)</label>
                            <div class="modal-form-field-wrapper">
                                <input type="number" 
                                       name="prix_max" 
                                       value="{{ request('prix_max') }}"
                                       min="0"
                                       step="0.01"
                                       placeholder="Max"
                                       class="modal-form-input"
                                       style="border: none;">
                            </div>
                        </div>
                    </div>

                    {{-- Tri --}}
                    <div class="grid grid-cols-2 gap-4" style="padding: 20px;">
                        <div>
                            <label class="modal-form-label">Trier par</label>
                            <div class="modal-form-field-wrapper">
                                <select name="sort" class="modal-form-select" style="border: none;">
                                    <option value="nom" {{ request('sort') == 'nom' ? 'selected' : '' }} style="background: #ffffff; color: #1b1b18;">Nom</option>
                                    <option value="prix" {{ request('sort') == 'prix' ? 'selected' : '' }} style="background: #ffffff; color: #1b1b18;">Prix</option>
                                    <option value="date" {{ request('sort') == 'date' ? 'selected' : '' }} style="background: #ffffff; color: #1b1b18;">Date de création</option>
                                    <option value="stock" {{ request('sort') == 'stock' ? 'selected' : '' }} style="background: #ffffff; color: #1b1b18;">Stock</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="modal-form-label">Ordre</label>
                            <div class="modal-form-field-wrapper">
                                <select name="order" class="modal-form-select" style="border: none;">
                                    <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }} style="background: #ffffff; color: #1b1b18;">Croissant</option>
                                    <option value="desc" {{ request('order') == 'desc' ? 'selected' : '' }} style="background: #ffffff; color: #1b1b18;">Décroissant</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Boutons --}}
                    <div class="flex gap-4 pt-4" style="display: flex; flex-direction: column; flex-wrap: nowrap; align-content: center; justify-content: center; align-items: center; padding: 20px; row-gap: 20px;">
                        <div class="relative mx-auto" style="display: inline-block; width: 200px;">
                            <img src="{{ asset('images/btn.png') }}" alt="" class="w-full h-auto block">
                            <button type="submit"
                                    class="absolute inset-0 w-full h-full flex items-center justify-center hover:opacity-90 transition-opacity duration-200"
                                    style="background: transparent; border: none; cursor: pointer; padding: 0;">
                                <span class="text-white font-bold text-base md:text-lg" style="font-family: 'Minecrafter Alt', sans-serif; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);">
                                    Filtrer
                                </span>
                            </button>
                        </div>
                        <a href="{{ route('produits.index') }}" class="px-6 py-3 bg-gray-200 rounded-lg font-bold transition-colors duration-200 flex items-center justify-center hover:text-[#5baa47]" style="font-family: 'Minecrafter Alt', sans-serif; color: #1b1b18;">
                            Reinitialiser
                        </a>
                    </div>
                </form>
            </div>
        </div>

        @if($canAddProduct ?? false)
        <div id="add-product-modal" class="hidden modal-form-backdrop">
            <div class="modal-form-container">
                <div class="modal-form-header">
                    <h2 class="modal-form-title">Ajouter un produit</h2>
                    <button id="close-add-product-btn" class="modal-form-close-button hover:opacity-80 transition-opacity">
                        <img src="{{ asset('images/cross.png') }}" alt="Fermer" class="h-6 w-6">
                    </button>
                </div>
                <form method="POST" action="{{ route('produits.store') }}" enctype="multipart/form-data" class="space-y-4" style="display: flex; flex-direction: column;">
                    @csrf
                    <div class="grid grid-cols-2 gap-4" style="padding: 20px;">
                        <div>
                            <label class="modal-form-label">Nom</label>
                            <div class="modal-form-field-wrapper">
                                <input type="text" name="nom" class="modal-form-input" style="border: none;">
                            </div>
                        </div>
                        <div>
                            <label class="modal-form-label">Référence</label>
                            <div class="modal-form-field-wrapper">
                                <input type="text" name="reference" class="modal-form-input" style="border: none;">
                            </div>
                        </div>
                    </div>
                    <div style="padding: 20px;">
                        <label class="modal-form-label">Description</label>
                        <div class="modal-form-field-wrapper">
                            <textarea name="description" class="modal-form-textarea" rows="4" style="border: none;"></textarea>
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-4" style="padding: 20px;">
                        <div>
                            <label class="modal-form-label">Prix (€)</label>
                            <div class="modal-form-field-wrapper">
                                <input type="number" name="prix" step="0.01" min="0" class="modal-form-input" style="border: none;">
                            </div>
                        </div>
                        <div>
                            <label class="modal-form-label">Stock</label>
                            <div class="modal-form-field-wrapper">
                                <input type="number" name="stock" min="0" class="modal-form-input" style="border: none;">
                            </div>
                        </div>
                        <div>
                            <label class="modal-form-label">PEGI (optionnel)</label>
                            <div class="modal-form-field-wrapper">
                                <input type="text" name="pegi" class="modal-form-input" style="border: none;">
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4" style="padding: 20px;">
                        <div>
                            <label class="block text-sm font-medium text-[#1b1b18] mb-2" style="font-family: 'Minecrafter Alt', sans-serif;">Catégorie</label>
                            <select name="categorie_id" class="w-full px-3 py-2 bg-white rounded border border-[#e3e3e0]" style="font-family: 'Minecrafter Alt', sans-serif;">
                                @foreach(($categories ?? []) as $cat)
                                    <option value="{{ $cat->id_categorie }}">{{ $cat->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-[#1b1b18] mb-2" style="font-family: 'Minecrafter Alt', sans-serif;">Image</label>
                            <input type="file" name="image" accept="image/*" class="w-full px-3 py-2 bg-white rounded border border-[#e3e3e0]" style="font-family: 'Minecrafter Alt', sans-serif;">
                        </div>
                    </div>
                    <div class="flex gap-4 pt-4" style="display: flex; flex-direction: column; flex-wrap: nowrap; align-content: center; justify-content: center; align-items: center; padding: 20px; row-gap: 20px;">
                        <div class="relative mx-auto" style="display: inline-block; width: 200px;">
                            <img src="{{ asset('images/btn.png') }}" alt="" class="w-full h-auto block">
                            <button type="submit"
                                    class="absolute inset-0 w-full h-full flex items-center justify-center hover:opacity-90 transition-opacity duration-200"
                                    style="background: transparent; border: none; cursor: pointer; padding: 0;">
                                <span class="text-white font-bold text-base md:text-lg" style="font-family: 'Minecrafter Alt', sans-serif; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);">
                                    Ajouter
                                </span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @endif
        
        @if($produits->isEmpty())
            <p class="text-[#706f6c]" style="font-family: 'Minecrafter Alt', sans-serif;">Aucun produit disponible pour le moment.</p>
        @else
            {{-- Grille de produits --}}
            <div class="Allproduits grid gap-6" style="gap: 2.5%; grid-template-columns: repeat(4, minmax(0, 1fr));">
                @foreach($produits as $produit)
                    @include('partials.product-card', [
                        'name' => $produit->nom,
                        'description' => $produit->description ?? '',
                        'price' => number_format($produit->prix, 2, ',', ' '),
                        'image' => $produit->image ? asset($produit->image) : asset('images/placeholder-product.png'),
                        'productId' => $produit->id_produit
                    ])
                @endforeach
            </div>
        @endif
    </div>

    @push('scripts')
    <script>
        // Gestion de la popup de filtrage
        const openFilterBtn = document.getElementById('open-filter-btn');
        const closeFilterBtn = document.getElementById('close-filter-btn');
        const filterModal = document.getElementById('filter-modal');
        const openAddProductBtn = document.getElementById('open-add-product-btn');
        const closeAddProductBtn = document.getElementById('close-add-product-btn');
        const addProductModal = document.getElementById('add-product-modal');

        openFilterBtn?.addEventListener('click', function() {
            filterModal.classList.remove('hidden');
        });

        closeFilterBtn?.addEventListener('click', function() {
            filterModal.classList.add('hidden');
        });

        // Fermer la popup en cliquant sur le fond
        filterModal?.addEventListener('click', function(e) {
            if (e.target === filterModal) {
                filterModal.classList.add('hidden');
            }
        });

        openAddProductBtn?.addEventListener('click', function() {
            addProductModal.classList.remove('hidden');
        });

        closeAddProductBtn?.addEventListener('click', function() {
            addProductModal.classList.add('hidden');
        });

        addProductModal?.addEventListener('click', function(e) {
            if (e.target === addProductModal) {
                addProductModal.classList.add('hidden');
            }
        });

        // Fermer avec la touche Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                if (!filterModal.classList.contains('hidden')) {
                    filterModal.classList.add('hidden');
                }
                if (addProductModal && !addProductModal.classList.contains('hidden')) {
                    addProductModal.classList.add('hidden');
                }
            }
        });
    </script>
    @endpush
@endsection
