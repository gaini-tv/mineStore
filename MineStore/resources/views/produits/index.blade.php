@extends('layouts.app')

@section('title', 'Nos produits')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/modal-form.css') }}">
@endpush

@section('content')
    {{-- Bannière avec titre et barre de recherche --}}
    <div class="w-full mb-8 relative">
        <img src="{{ asset('images/banierP.png') }}" alt="Bannière produits" class="w-full h-auto">
        <div style="position: absolute; inset: 0; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 0 1rem;">
            <h1 style="font-family: 'Minecrafter Alt', sans-serif; font-size: 6rem; font-weight: bold; color: white; text-shadow: 3px 3px 6px rgba(0, 0, 0, 0.9); margin: 0;">
                Nos produits
            </h1>
            <form method="GET" action="{{ route('produits.index') }}" id="product-search-form" style="width: 100%; max-width: 600px; margin-top: 2rem;">
                <input type="hidden" name="categorie_id" value="{{ request('categorie_id') }}">
                <div class="searchbar-container flex gap-2" style="background-image: url('{{ asset('images/searchbar.png') }}'); background-size: 100% 100%; background-position: center; background-repeat: no-repeat; border: none; border-radius: 0; padding: 0.25rem 0.75rem; min-height: 40px;">
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Recherche..."
                           style="flex: 1; padding: 0.25rem 0.5rem; border: 0; background: transparent; color: white; font-family: 'Minecrafter Alt', sans-serif; font-size: 0.9rem; outline: none;"
                           class="placeholder-[#706f6c]">
                    <button type="submit"
                            style="padding: 0.25rem 0.75rem; background: transparent; border: none; cursor: pointer;"
                            class="hover:opacity-80 transition-opacity flex items-center justify-center">
                        <img src="{{ asset('images/iconrecherce.png') }}" alt="Rechercher" style="height: 1.25rem; width: 1.25rem;">
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <div class="container mx-auto px-4 py-8" style="padding-bottom: 5rem; ">
        <div class="ProductNav mb-6" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; margin-bottom: 1rem;">
            {{-- Gauche : nombre de produits trouvés --}}
            <p style="font-family: 'Minecrafter Alt', sans-serif; color: #706f6c; margin: 0;">
                {{ $produits->count() }} produit(s) trouvé(s)
            </p>

            {{-- Droite : filtrage + bouton ajouter --}}
            <div style="display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;">
                <form method="GET" action="{{ route('produits.index') }}" id="product-category-form" style="display: inline;">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <select
                        name="categorie_id"
                        style="padding: 0.5rem 1rem; background: white; border: 1px solid #e3e3e0; border-radius: 4px; font-family: 'Minecrafter Alt', sans-serif; color: #1b1b18; font-size: 0.875rem;"
                        onchange="this.form.dispatchEvent(new Event('submit', { cancelable: true }))"
                    >
                        <option value="">
                            Toutes les catégories ({{ $totalProduitsActifs ?? 0 }})
                        </option>
                        @foreach(($categories ?? []) as $cat)
                            <option
                                value="{{ $cat->id_categorie }}"
                                @selected(request('categorie_id') == $cat->id_categorie)
                            >
                                {{ $cat->nom }}
                                ({{ $cat->produits_count ?? 0 }})
                            </option>
                        @endforeach
                    </select>
                </form>
                <button id="open-filter-btn"
                        type="button"
                        style="padding: 0.5rem 1rem; background: white; border: 1px solid #e3e3e0; border-radius: 4px; font-family: 'Minecrafter Alt', sans-serif; cursor: pointer; display: flex; align-items: center; gap: 0.5rem;">
                    <span>Filtre</span>
                    <img src="{{ asset('images/filtre.png') }}" alt="Filtrer" style="height: 1.5rem; width: 1.5rem;">
                </button>
                @if($canAddProduct ?? false)
                    <div class="relative btn-panier-wrapper" style="display: inline-block; width: 300px; margin: 0;">
                        <img src="{{ asset('images/btn.png') }}" alt="" style="width: 100%; height: auto; display: block;">
                        <button type="button"
                                id="open-add-product-btn"
                                style="position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; background: transparent; border: none; cursor: pointer; padding: 0; width: 100%; height: 100%;">
                            <span style="color: white; font-weight: bold; font-size: 1rem; font-family: 'Minecrafter Alt', sans-serif; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);">
                                AJOUTER UN PRODUIT
                            </span>
                        </button>
                    </div>
                @endif
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
                    <div class="grid grid-cols-2 gap-4" style="padding: 20px;">
                        <div>
                            <label class="modal-form-label">Prix (€)</label>
                            <div class="modal-form-field-wrapper">
                                <input type="number" name="prix" step="0.01" min="0" class="modal-form-input" style="border: none;">
                            </div>
                        </div>
                        <div>
                            <label class="modal-form-label" style="margin-bottom: 0.25rem;">
                                <input type="checkbox" name="infinite_stock" id="infinite_stock" value="1" class="h-4 w-4" style="margin-right: 0.5rem;">
                                Stock infini
                            </label>
                            <label class="modal-form-label" id="stock_label">Stock</label>
                            <div class="modal-form-field-wrapper" style="margin-bottom: 0.5rem;">
                                <input type="number" name="stock" id="stock_input" min="0" class="modal-form-input" style="border: none;">
                            </div>
                            <label class="modal-form-label" id="stock_low_threshold_label">Seuil "stock faible"</label>
                            <div class="modal-form-field-wrapper" style="margin-bottom: 0.5rem;">
                                <input type="number" name="stock_low_threshold" id="stock_low_threshold" min="1" value="100" class="modal-form-input" style="border: none;">
                            </div>
                            <label class="modal-form-label">PEGI (optionnel)</label>
                            <div class="modal-form-field-wrapper">
                                <select name="pegi" class="modal-form-select" style="border: none;">
                                    <option value="">Aucun</option>
                                    <option value="images/pegi3.png">PEGI 3</option>
                                    <option value="images/pegi7.png">PEGI 7</option>
                                    <option value="images/pegi12.png">PEGI 12</option>
                                    <option value="images/pegi16.png">PEGI 16</option>
                                    <option value="images/pegi18.png">PEGI 18</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4" style="padding: 20px;">
                        <div>
                            <label class="modal-form-label">Catégorie</label>
                            <div class="modal-form-field-wrapper">
                                <select name="categorie_id" class="modal-form-select" style="border: none;">
                                    @foreach(($categories ?? []) as $cat)
                                        <option value="{{ $cat->id_categorie }}" @if(mb_strtolower(trim($cat->nom)) === mb_strtolower('Non catégorisé')) selected @endif>{{ $cat->nom }}</option>
                                    @endforeach
                                </select>
                            </div>
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
            <div class="Allproduits grid gap-6" style="gap: 2.5%; grid-template-columns: repeat(4, minmax(0, 1fr)); margin: 1.5rem;">
                @foreach($produits as $produit)
                    @include('partials.product-card', [
                        'name' => $produit->nom,
                        'description' => $produit->description ?? '',
                        'price' => number_format($produit->prix, 2, ',', ' '),
                        'image' => $produit->image ? asset($produit->image) : asset('images/placeholder-product.png'),
                        'productId' => $produit->id_produit,
                        'stock' => $produit->stock,
                        'infiniteStock' => $produit->infinite_stock,
                        'ruptureMarketing' => $produit->rupture_marketing ?? false
                    ])
                @endforeach
            </div>
        @endif
    </div>

    @push('scripts')
    <script>
        function updateProductsFromUrl(url) {
            return fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(function (res) { return res.text(); })
                .then(function (html) {
                    var parser = new DOMParser();
                    var doc = parser.parseFromString(html, 'text/html');
                    var newGrid = doc.querySelector('.Allproduits');
                    var newCount = doc.querySelector('.TextResult p');
                    var grid = document.querySelector('.Allproduits');
                    var count = document.querySelector('.TextResult p');
                    if (newGrid && grid) {
                        grid.innerHTML = newGrid.innerHTML;
                    }
                    if (newCount && count) {
                        count.textContent = newCount.textContent;
                    }
                });
        }

        var searchForm = document.getElementById('product-search-form');
        var searchInput = searchForm ? searchForm.querySelector('input[name="search"]') : null;
        var categoryForm = document.getElementById('product-category-form');
        var filterForm = document.querySelector('#filter-modal form[action*="produits.index"]');

        if (searchForm) {
            searchForm.addEventListener('submit', function (e) {
                e.preventDefault();
                var params = new URLSearchParams(new FormData(searchForm));
                var url = searchForm.action + '?' + params.toString();
                updateProductsFromUrl(url);
            });
        }

        if (searchInput) {
            var tId;
            searchInput.addEventListener('input', function () {
                clearTimeout(tId);
                var value = this.value || '';
                tId = setTimeout(function () {
                    var params = new URLSearchParams(new FormData(searchForm));
                    var url = searchForm.action + '?' + params.toString();
                    updateProductsFromUrl(url);
                }, 250);
            });
            searchInput.addEventListener('keydown', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    e.stopPropagation();
                }
            });
        }

        if (categoryForm) {
            categoryForm.addEventListener('submit', function (e) {
                e.preventDefault();
                var params = new URLSearchParams(new FormData(categoryForm));
                var url = categoryForm.action + '?' + params.toString();
                updateProductsFromUrl(url);
            });
        }

        if (filterForm) {
            filterForm.addEventListener('submit', function (e) {
                e.preventDefault();
                var params = new URLSearchParams(new FormData(filterForm));
                var url = filterForm.action + '?' + params.toString();
                updateProductsFromUrl(url).then(function () {
                    var filterModalEl = document.getElementById('filter-modal');
                    if (filterModalEl) {
                        filterModalEl.classList.add('hidden');
                    }
                });
            });
        }

        // Gestion du stock infini dans le formulaire d'ajout de produit
        var infiniteCheckbox = document.getElementById('infinite_stock');
        var stockInput = document.getElementById('stock_input');
        var stockLowThresholdInput = document.getElementById('stock_low_threshold');
        var stockLowThresholdLabel = document.getElementById('stock_low_threshold_label');
        var stockLabel = document.getElementById('stock_label');

        function updateStockInfiniteState() {
            if (!infiniteCheckbox || !stockInput || !stockLowThresholdInput) {
                return;
            }
            if (infiniteCheckbox.checked) {
                stockInput.value = '';
                stockInput.disabled = true;
                stockLowThresholdInput.disabled = true;
                stockInput.classList.add('modal-form-input-disabled');
                stockLowThresholdInput.classList.add('modal-form-input-disabled');
                if (stockLabel) {
                    stockLabel.classList.add('modal-form-label-disabled');
                }
                if (stockLowThresholdLabel) {
                    stockLowThresholdLabel.classList.add('modal-form-label-disabled');
                }
            } else {
                stockInput.disabled = false;
                stockLowThresholdInput.disabled = false;
                stockInput.classList.remove('modal-form-input-disabled');
                stockLowThresholdInput.classList.remove('modal-form-input-disabled');
                if (stockLabel) {
                    stockLabel.classList.remove('modal-form-label-disabled');
                }
                if (stockLowThresholdLabel) {
                    stockLowThresholdLabel.classList.remove('modal-form-label-disabled');
                }
            }
        }

        if (infiniteCheckbox) {
            infiniteCheckbox.addEventListener('change', updateStockInfiniteState);
            updateStockInfiniteState();
        }

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
