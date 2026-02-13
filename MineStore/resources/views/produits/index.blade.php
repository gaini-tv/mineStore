@extends('layouts.app')

@section('title', 'Nos produits')

@section('content')
    {{-- Bannière avec texte centré --}}
    <div class="w-full mb-8 relative">
        <img src="{{ asset('images/banierP.png') }}" alt="Bannière produits" class="w-full h-auto">
        <h1 class="absolute inset-0 flex items-center justify-center font-bold text-white" style="font-family: 'Minecrafter Alt', sans-serif; font-size: 6rem; text-shadow: 3px 3px 6px rgba(0, 0, 0, 0.9);">
            Nos produits
        </h1>
    </div>
    
    <div class="container mx-auto px-4 py-8" ">
        {{-- Section résultats et filtre --}}
        <div class="flex justify-between items-center mb-6">
            <p class="text-[#706f6c]" style="font-family: 'Minecrafter Alt', sans-serif;">
                {{ $produits->count() }} produit(s) trouvé(s)
            </p>
            <button id="open-filter-btn" class="cursor-pointer hover:opacity-80 transition-opacity">
                <img src="{{ asset('images/filtre.png') }}" alt="Filtrer" class="h-8 w-8">
            </button>
        </div>

        {{-- Popup de filtrage --}}
        <div id="filter-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-lg shadow-lg p-6 max-w-2xl w-full max-h-[90vh] overflow-y-auto" style="border: 10px solid #5baa47;">
                <div class="flex justify-between items-center mb-4" style="display: flex; flex-wrap: nowrap; align-content: center; justify-content: center; align-items: center; flex-direction: column-reverse;">
                    <h2 class="text-2xl font-bold text-[#1b1b18]" style="font-family: 'Minecrafter Alt', sans-serif;">Filtrer les produits</h2>
                    <button id="close-filter-btn" class="cursor-pointer hover:opacity-80 transition-opacity">
                        <img src="{{ asset('images/cross.png') }}" alt="Fermer" class="h-6 w-6">
                    </button>
                </div>
                
                <form method="GET" action="{{ route('produits.index') }}" class="space-y-4" style="display: flex; flex-direction: column; ">
                    {{-- Recherche par nom --}}
                    <div style="padding: 20px;">
                        <label class="block text-sm font-medium text-[#1b1b18] mb-2" style="font-family: 'Minecrafter Alt', sans-serif;">Rechercher un produit</label>
                        <div class="p-2" style="background-image: url('{{ asset('images/searchbar.png') }}'); background-size: 100% 100%; background-position: center; background-repeat: no-repeat; border: none; border-radius: 0;">
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Nom du produit..."
                                   class="w-full px-3 py-2 border-0 bg-transparent text-white placeholder-[#706f6c] focus:outline-none"
                                   style="font-family: 'Minecrafter Alt', sans-serif; border-radius: 0; border: none;">
                        </div>
                    </div>

                    {{-- Filtre par prix --}}
                    <div class="grid grid-cols-2 gap-4" style="padding: 20px;">
                        <div>
                            <label class="block text-sm font-medium text-[#1b1b18] mb-2" style="font-family: 'Minecrafter Alt', sans-serif;">Prix min (€)</label>
                            <div class="p-2" style="background-image: url('{{ asset('images/searchbar.png') }}'); background-size: 100% 100%; background-position: center; background-repeat: no-repeat; border: none; border-radius: 0;">
                                <input type="number" 
                                       name="prix_min" 
                                       value="{{ request('prix_min') }}"
                                       min="0"
                                       step="0.01"
                                       placeholder="Min"
                                       class="w-full px-3 py-2 border-0 bg-transparent text-white placeholder-[#706f6c] focus:outline-none"
                                       style="font-family: 'Minecrafter Alt', sans-serif; border-radius: 0; border: none;">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-[#1b1b18] mb-2" style="font-family: 'Minecrafter Alt', sans-serif;">Prix max (€)</label>
                            <div class="p-2" style="background-image: url('{{ asset('images/searchbar.png') }}'); background-size: 100% 100%; background-position: center; background-repeat: no-repeat; border: none; border-radius: 0;">
                                <input type="number" 
                                       name="prix_max" 
                                       value="{{ request('prix_max') }}"
                                       min="0"
                                       step="0.01"
                                       placeholder="Max"
                                       class="w-full px-3 py-2 border-0 bg-transparent text-white placeholder-[#706f6c] focus:outline-none"
                                       style="font-family: 'Minecrafter Alt', sans-serif; border-radius: 0; border: none;">
                            </div>
                        </div>
                    </div>

                    {{-- Tri --}}
                    <div class="grid grid-cols-2 gap-4" style="padding: 20px;">
                        <div>
                            <label class="block text-sm font-medium text-[#1b1b18] mb-2" style="font-family: 'Minecrafter Alt', sans-serif;">Trier par</label>
                            <div class="p-2" style="background-image: url('{{ asset('images/searchbar.png') }}'); background-size: 100% 100%; background-position: center; background-repeat: no-repeat; border: none; border-radius: 0;">
                                <select name="sort" class="w-full px-3 py-2 border-0 bg-transparent text-white focus:outline-none" style="font-family: 'Minecrafter Alt', sans-serif; border-radius: 0; border: none; color: white;">
                                    <option value="nom" {{ request('sort') == 'nom' ? 'selected' : '' }} style="background: #1b1b18; color: white;">Nom</option>
                                    <option value="prix" {{ request('sort') == 'prix' ? 'selected' : '' }} style="background: #1b1b18; color: white;">Prix</option>
                                    <option value="date" {{ request('sort') == 'date' ? 'selected' : '' }} style="background: #1b1b18; color: white;">Date de création</option>
                                    <option value="stock" {{ request('sort') == 'stock' ? 'selected' : '' }} style="background: #1b1b18; color: white;">Stock</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-[#1b1b18] mb-2" style="font-family: 'Minecrafter Alt', sans-serif;">Ordre</label>
                            <div class="p-2" style="background-image: url('{{ asset('images/searchbar.png') }}'); background-size: 100% 100%; background-position: center; background-repeat: no-repeat; border: none; border-radius: 0;">
                                <select name="order" class="w-full px-3 py-2 border-0 bg-transparent text-white focus:outline-none" style="font-family: 'Minecrafter Alt', sans-serif; border-radius: 0; border: none; color: white;">
                                    <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }} style="background: #1b1b18; color: white;">Croissant</option>
                                    <option value="desc" {{ request('order') == 'desc' ? 'selected' : '' }} style="background: #1b1b18; color: white;">Décroissant</option>
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
        
        @if($produits->isEmpty())
            <p class="text-[#706f6c]" style="font-family: 'Minecrafter Alt', sans-serif;">Aucun produit disponible pour le moment.</p>
        @else
            {{-- Grille de produits --}}
            <div class="Allproduits grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
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

        // Fermer avec la touche Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !filterModal.classList.contains('hidden')) {
                filterModal.classList.add('hidden');
            }
        });
    </script>
    @endpush
@endsection
