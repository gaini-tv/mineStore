@extends('layouts.app')

@section('title', $produit->nom)

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/modal-form.css') }}">
@endpush

@section('content')
    @php
        $stockValue = $produit->stock;
        $lowThreshold = $produit->stock_low_threshold ?? 100;
        $infiniteStock = $produit->infinite_stock ?? false;
        $ruptureMarketing = $produit->rupture_marketing ?? false;

        if ($ruptureMarketing) {
            $stockStatusLabel = 'Rupture de stock';
            $stockStatusColor = '#f44336';
            $isOutOfStock = true;
        } elseif ($infiniteStock) {
            $stockStatusLabel = 'En stock';
            $stockStatusColor = '#5baa47';
            $isOutOfStock = false;
        } elseif ($stockValue <= 0) {
            $stockStatusLabel = 'Rupture de stock';
            $stockStatusColor = '#f44336';
            $isOutOfStock = true;
        } elseif ($stockValue < $lowThreshold) {
            $stockStatusLabel = 'Stock faible';
            $stockStatusColor = '#ff9800';
            $isOutOfStock = false;
        } else {
            $stockStatusLabel = 'En stock';
            $stockStatusColor = '#5baa47';
            $isOutOfStock = false;
        }
    @endphp

    <div class="container mx-auto px-4 py-8" style="padding-top: 200px;">
        <div class="bg-white rounded-lg shadow-md overflow-hidden" style="width: 100%;">
            <div class="info" style="display: grid; grid-template-columns: repeat(3, 1fr); grid-gap: 2rem;">
                {{-- Image du produit --}}
                <div class="flex items-center justify-center p-8 bg-gray-50" style="grid-column: 1;">
                    <img src="{{ $produit->image ? asset($produit->image) : asset('images/placeholder-product.png') }}"
                         alt="{{ $produit->nom }}"
                         class="transition-all duration-300 hover:scale-105 hover:opacity-90 cursor-pointer"
                         style="max-width: 100%; height: auto; max-height: 100%; object-fit: contain; display: block;">
                </div>

                {{-- Informations du produit --}}
                <div class="p-8 flex flex-col justify-center relative" style="grid-column: 2 / 4; margin-right: 4rem; display: flex; flex-direction: column; flex-wrap: nowrap; align-content: center; justify-content: center; align-items: flex-start; row-gap: 30px;">
                    {{-- Bouton Retour --}}
                    <a href="{{ route('produits.index') }}" 
                       class="transition-colors duration-200 inline-flex items-center gap-2" style="color: #5baa47;">
                        <span>← Retour aux produits</span>
                    </a>
                    
                    {{-- Titre avec stock à droite --}}
                    <div class="w-full flex items-center justify-between relative" style="width: 100%;">
                        <h1 class="text-3xl md:text-4xl font-bold text-[#1b1b18]" style="font-family: 'Minecrafter Alt', sans-serif;">
                            {{ $produit->nom }}
                        </h1>
                        <span class="font-semibold absolute right-0" style="color: {{ $stockStatusColor }}; font-family: 'Minecrafter Alt', sans-serif;">
                            {{ $stockStatusLabel }}
                        </span>
                    </div>

                    @if($produit->description)
                        <p class="text-[#706f6c] text-lg mb-6" style="text-align: justify;">
                            {{ $produit->description }}
                        </p>
                    @endif

                    <div class="mb-6">
                        <div class="flex items-center gap-4 mb-4">
                            <span class="text-3xl font-bold text-[#1b1b18]" style="font-family: 'Minecrafter Alt', sans-serif;">
                                {{ number_format($produit->prix, 2, ',', ' ') }} €
                            </span>
                        </div>
                        @if($produit->reference)
                            <p class="text-sm text-[#706f6c]">Référence : {{ $produit->reference }}</p>
                        @endif
                    </div>

                    {{-- Bouton Ajouter au panier avec actions de gestion --}}
                    <div class="relative mb-4" style="position: relative; width: 100%; display: flex; align-items: flex-end; gap: 16px;">
                        <form method="POST" action="{{ route('panier.add', $produit->id_produit) }}">
                            @csrf
                            <div class="relative btn-panier-wrapper {{ $isOutOfStock ? 'btn-panier-wrapper-disabled' : '' }}" style="display: inline-block; width: 300px;">
                                <img src="{{ asset('images/btnpanier.png') }}" alt="" class="w-full h-auto block">
                                <button type="submit"
                                        class="absolute inset-0 w-full h-full flex items-center justify-center hover:opacity-90 transition-opacity duration-200 btn-panier {{ $isOutOfStock ? 'btn-panier-disabled' : '' }}"
                                        style="background: transparent; border: none; padding: 0;"
                                        @if($isOutOfStock) disabled @endif>
                                    <span class="text-white font-bold" style="font-size: 1rem; font-family: 'Minecrafter Alt', sans-serif; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);">
                                        AJOUTER AU PANIER
                                    </span>
                                </button>
                            </div>
                        </form>
                        
                        @if($canManageProduct ?? false)
                            <div class="flex gap-3">
                                <button type="button"
                                        id="open-edit-product-btn"
                                        class="px-4 py-2 btn-scale"
                                        style="background-color: #5baa47; color: #ffffff; font-family: 'Minecrafter Alt', sans-serif; border: 2px solid #3f7c33; cursor: pointer;">
                                    Modifier le produit
                                </button>
                                <button type="button"
                                        id="open-delete-product-btn"
                                        class="px-4 py-2 btn-scale"
                                        style="background-color: #b91c1c; color: #ffffff; font-family: 'Minecrafter Alt', sans-serif; border: 2px solid #7f1d1d; cursor: pointer;">
                                    Supprimer
                                </button>
                            </div>
                        @endif

                        {{-- Image PEGI collée à droite en bas --}}
                        @if($produit->pegi)
                            <img src="{{ asset($produit->pegi) }}" 
                                 alt="PEGI" 
                                 class="absolute h-16 w-auto"
                                 style="bottom: 0; right: 0;">
                        @endif
                    </div>

                    
                </div>
            </div>
        </div>

        {{-- Séparateur vert --}}
        <div class="flex justify-center" style="padding-top: 40px; padding-bottom: 40px;">
            <div style="border-top: 2px solid #5baa47; width: 60%;"></div>
        </div>

        {{-- Section Commentaires --}}
        <div class="mt-12 pt-12 pb-12" style="padding-left: 50px; padding-right: 50px;">
            <h2 class="text-3xl font-bold text-white mb-6" style="font-family: 'Minecrafter Alt', sans-serif; padding-bottom: 30px; padding-top: 30px; align-items: center; display: flex; justify-content: center; align-content: center; flex-wrap: nowrap; flex-direction: row; text-shadow: -1px -1px 0 #000, 1px -1px 0 #000, -1px 1px 0 #000, 1px 1px 0 #000, 0 0 10px rgba(0, 150, 0, 0.8), 0 0 20px rgba(0, 150, 0, 0.6), 0 0 30px rgba(0, 150, 0, 0.4);">
                Commentaires
            </h2>
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Formulaire d'ajout de commentaire --}}
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                @auth
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-bold" style="font-family: 'Minecrafter Alt', sans-serif; color: #1b1b18;">
                            Laisser un commentaire
                        </h3>
                        {{-- Notation par étoiles --}}
                        <div class="flex gap-2 items-center" id="star-rating">
                            @for($i = 1; $i <= 5; $i++)
                                <button type="button" class="star-button cursor-pointer" data-rating="{{ $i }}">
                                    <img src="https://minecraft.wiki/images/Nether_Star.gif?fb01f&format=original" 
                                         alt="Étoile" 
                                         class="star-image"
                                         style="opacity: 0.3; width: 60px; height: 60px;">
                                </button>
                            @endfor
                        </div>
                    </div>
                    <form action="{{ route('commentaires.store', $produit->id_produit) }}" method="POST">
                        @csrf
                        <input type="hidden" name="note" id="note-value" value="" required>
                        <p class="text-red-500 text-sm mt-1 hidden" id="note-error">Veuillez sélectionner une note en cliquant sur les étoiles.</p>
                        @error('note')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror

                        {{-- Commentaire --}}
                        <div class="mb-4">
                            <label for="contenu" class="block text-sm font-medium text-gray-700 mb-2" style="font-family: 'Minecrafter Alt', sans-serif;">
                                Votre commentaire
                            </label>
                            <textarea name="contenu" id="contenu" rows="4" 
                                      class="w-full px-4 py-2 focus:outline-none"
                                      style="background-image: url('{{ asset('images/searchbar.png') }}'); background-size: 100% 100%; background-position: center; background-repeat: no-repeat; border: none; border-radius: 0; min-height: 100px;"
                                      placeholder="Écrivez votre commentaire ici..." required></textarea>
                            @error('contenu')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Bouton soumettre --}}
                        <div class="relative" style="display: inline-block; width: 200px;">
                            <img src="{{ asset('images/btn.png') }}" alt="" class="w-full h-auto block" id="btn-image">
                            <button type="submit" id="submit-comment-btn"
                                    class="absolute inset-0 w-full h-full flex items-center justify-center hover:opacity-90 transition-opacity duration-200"
                                    style="background: transparent; border: none; cursor: not-allowed; padding: 0; opacity: 0.5;" disabled>
                                <span class="text-white font-bold text-base md:text-lg" style="font-family: 'Minecrafter Alt', sans-serif; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);">
                                    Envoyer
                                </span>
                            </button>
                        </div>
                    </form>
                @else
                    <h3 class="text-xl font-bold mb-4" style="font-family: 'Minecrafter Alt', sans-serif; color: #1b1b18;">
                        Laisser un commentaire
                    </h3>
                    <p class="text-gray-700 mb-4" style="font-family: 'Minecrafter Alt', sans-serif;">
                        Vous devez être connecté pour laisser un commentaire.
                    </p>
                    <div class="relative" style="display: inline-block; width: 200px;">
                        <img src="{{ asset('images/btn.png') }}" alt="" class="w-full h-auto block">
                        <a href="{{ route('login') }}"
                           class="absolute inset-0 w-full h-full flex items-center justify-center hover:opacity-90 transition-opacity duration-200"
                           style="background: transparent; border: none; padding: 0; text-decoration: none;">
                            <span class="text-white font-bold text-base md:text-lg" style="font-family: 'Minecrafter Alt', sans-serif; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);">
                                Se connecter
                            </span>
                        </a>
                    </div>
                @endauth
            </div>

            {{-- Section commentaires avec menu déroulant --}}
            <div class="bg-white rounded-lg shadow-md mb-8">
                {{-- En-tête cliquable --}}
                <button id="toggle-comments" class="w-full flex items-center justify-between p-6 hover:bg-gray-50 transition-colors" style="font-family: 'Minecrafter Alt', sans-serif;">
                    <h3 class="text-xl font-bold text-[#1b1b18]">
                        Commentaires ({{ $commentaires && $commentaires->count() > 0 ? $commentaires->count() : 0 }})
                    </h3>
                    <svg id="arrow-icon" class="w-6 h-6 text-[#1b1b18] transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
                
                {{-- Contenu des commentaires (masqué par défaut) --}}
                <div id="comments-content" class="hidden">
                    @if($commentaires && $commentaires->count() > 0)
                        <div class="px-6 pb-6" style="display: flex; flex-direction: column; flex-wrap: nowrap; align-content: center; justify-content: center; align-items: center; row-gap: 20px; {{ $commentaires->count() > 3 ? 'max-height: 600px; overflow-y: auto;' : '' }}">
                            @foreach($commentaires as $index => $commentaire)
                                <div class="bg-gray-50 rounded-lg p-6 w-full" style="{{ $index < $commentaires->count() - 1 ? 'border-bottom: 2px solid #e3e3e0; padding-bottom: 20px;' : '' }}">
                                    <div class="flex items-start justify-between mb-3">
                                        <div class="flex items-start gap-4 flex-1">
                                            {{-- Avatar à gauche --}}
                                            @php
                                                $userAvatar = $commentaire->user && $commentaire->user->avatar 
                                                    ? asset('images/avatar/' . $commentaire->user->avatar) 
                                                    : asset('images/avatar/base.png');
                                            @endphp
                                            <div class="w-16 h-16 rounded border-2 border-[#5baa47] overflow-hidden flex-shrink-0" style="aspect-ratio: 1/1;">
                                                <img src="{{ $userAvatar }}" 
                                                     alt="Avatar" 
                                                     class="w-full h-full"
                                                     style="object-fit: contain;">
                                            </div>
                                            {{-- Nom et email à droite de l'avatar --}}
                                            <div class="flex-1">
                                                <h4 class="text-lg font-bold" style="font-family: 'Minecrafter Alt', sans-serif; color: #1b1b18;">
                                                    @if($commentaire->user)
                                                        {{ trim(($commentaire->user->prenom ?? '').' '.($commentaire->user->nom ?? '')) ?: 'Utilisateur anonyme' }}
                                                    @else
                                                        Utilisateur anonyme
                                                    @endif
                                                </h4>
                                                @if($commentaire->user && $commentaire->user->email)
                                                    <p class="text-sm text-gray-500" style="font-family: 'Minecrafter Alt', sans-serif;">
                                                        {{ $commentaire->user->email }}
                                                    </p>
                                                @endif
                                                <p class="text-sm text-gray-500">
                                                    {{ $commentaire->date_->format('d/m/Y à H:i') }}
                                                </p>
                                            </div>
                                        </div>
                                        {{-- Affichage des étoiles --}}
                                        <div class="flex items-center ml-4 gap-3">
                                            <div class="flex gap-1 items-center">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <img src="https://minecraft.wiki/images/Nether_Star.gif?fb01f&format=original" 
                                                         alt="Étoile" 
                                                         style="opacity: {{ $i <= ($commentaire->note ?? 0) ? '1.0' : '0.3' }}; width: 40px; height: 40px;">
                                                @endfor
                                            </div>
                                            @if(auth()->check() && (auth()->user()->role === 'admin' || auth()->id() === $commentaire->user_id))
                                                <form action="{{ route('commentaires.destroy', $commentaire->id_commentaire) }}" method="POST" onsubmit="return confirm('Supprimer ce commentaire ?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="px-3 py-1 text-sm"
                                                            style="background-color: #dc2626; color: #ffffff; font-family: 'Minecrafter Alt', sans-serif; border: 2px solid #7f1d1d; border-radius: 0.25rem; cursor: pointer;">
                                                        Supprimer
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                    <p class="text-gray-700" style="text-align: justify;">
                                        {{ $commentaire->contenu }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="px-6 pb-6 text-center">
                            <p class="text-gray-500" style="font-family: 'Minecrafter Alt', sans-serif;">
                                Aucun commentaire pour le moment. Soyez le premier à commenter !
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Modale d'édition de produit --}}
        @if($canManageProduct ?? false)
            @php
                $currentCategorieId = $produit->categories->first()->id_categorie ?? null;
            @endphp
            <div id="edit-product-modal" class="modal-form-backdrop hidden">
                <div class="modal-form-container">
                    <div class="modal-form-header">
                        <h2 class="modal-form-title">Modifier le produit</h2>
                        <button type="button" id="close-edit-product-btn" class="modal-form-close-button">
                            <img src="{{ asset('images/cross.png') }}" alt="Fermer" style="width: 24px; height: 24px;">
                        </button>
                    </div>
                    <form action="{{ route('produits.update', $produit->id_produit) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-2 gap-4" style="padding: 20px;">
                            <div>
                                <label class="modal-form-label">Nom du produit</label>
                                <div class="modal-form-field-wrapper">
                                    <input type="text" name="nom" class="modal-form-input" style="border: none;" value="{{ old('nom', $produit->nom) }}">
                                </div>
                            </div>
                            <div>
                                <label class="modal-form-label">Référence</label>
                                <div class="modal-form-field-wrapper">
                                    <input type="text" name="reference" class="modal-form-input" style="border: none;" value="{{ old('reference', $produit->reference) }}">
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4" style="padding: 20px;">
                            <div>
                                <label class="modal-form-label">Description</label>
                                <div class="modal-form-field-wrapper">
                                    <textarea name="description" rows="4" class="modal-form-textarea" style="border: none;">{{ old('description', $produit->description) }}</textarea>
                                </div>
                            </div>
                            <div>
                                <label class="modal-form-label">Prix (€)</label>
                                <div class="modal-form-field-wrapper">
                                    <input type="number" name="prix" step="0.01" min="0" class="modal-form-input" style="border: none;" value="{{ old('prix', $produit->prix) }}">
                                </div>
                                <label class="modal-form-label" style="margin-top: 1rem; margin-bottom: 0.25rem;">
                                    <input type="checkbox" name="infinite_stock" id="edit_infinite_stock" value="1" class="h-4 w-4" style="margin-right: 0.5rem;" {{ $produit->infinite_stock ? 'checked' : '' }}>
                                    Stock infini
                                </label>
                                <label class="modal-form-label" id="edit_stock_label">Stock</label>
                                <div class="modal-form-field-wrapper" style="margin-bottom: 0.5rem;">
                                    <input type="number" name="stock" id="edit_stock_input" min="0" class="modal-form-input" style="border: none;" value="{{ old('stock', $produit->stock) }}">
                                </div>
                                <label class="modal-form-label" id="edit_stock_low_threshold_label">Seuil "stock faible"</label>
                                <div class="modal-form-field-wrapper" style="margin-bottom: 0.5rem;">
                                    <input type="number" name="stock_low_threshold" id="edit_stock_low_threshold" min="1" class="modal-form-input" style="border: none;" value="{{ old('stock_low_threshold', $produit->stock_low_threshold ?? 100) }}">
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4" style="padding: 20px;">
                            <div>
                                <label class="modal-form-label">Catégorie</label>
                                <div class="modal-form-field-wrapper">
                                    <select name="categorie_id" class="modal-form-select" style="border: none;">
                                        @foreach(($categories ?? []) as $cat)
                                            <option value="{{ $cat->id_categorie }}" @if(($currentCategorieId && $currentCategorieId === $cat->id_categorie)) selected @endif>{{ $cat->nom }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="modal-form-label">PEGI (optionnel)</label>
                                <div class="modal-form-field-wrapper">
                                    <select name="pegi" class="modal-form-select" style="border: none;">
                                        <option value="">Aucun</option>
                                        <option value="images/pegi3.png" {{ old('pegi', $produit->pegi) === 'images/pegi3.png' ? 'selected' : '' }}>PEGI 3</option>
                                        <option value="images/pegi7.png" {{ old('pegi', $produit->pegi) === 'images/pegi7.png' ? 'selected' : '' }}>PEGI 7</option>
                                        <option value="images/pegi12.png" {{ old('pegi', $produit->pegi) === 'images/pegi12.png' ? 'selected' : '' }}>PEGI 12</option>
                                        <option value="images/pegi16.png" {{ old('pegi', $produit->pegi) === 'images/pegi16.png' ? 'selected' : '' }}>PEGI 16</option>
                                        <option value="images/pegi18.png" {{ old('pegi', $produit->pegi) === 'images/pegi18.png' ? 'selected' : '' }}>PEGI 18</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4" style="padding: 20px;">
                            <div>
                                <label class="modal-form-label">Image (laisser vide pour conserver l'actuelle)</label>
                                <input type="file" name="image" accept="image/*" class="w-full px-3 py-2 bg-white rounded border border-[#e3e3e0]" style="font-family: 'Minecrafter Alt', sans-serif;">
                            </div>
                        </div>
                        <div class="modal-form-footer">
                            <button type="button"
                                    id="cancel-edit-product-btn"
                                    class="px-4 py-2"
                                    style="background-color: #e5e7eb; color: #1f2933; font-family: 'Minecrafter Alt', sans-serif; border: 2px solid #9ca3af; cursor: pointer;">
                                Annuler
                            </button>
                            <button type="submit"
                                    class="px-4 py-2"
                                    style="background-color: #5baa47; color: #ffffff; font-family: 'Minecrafter Alt', sans-serif; border: 2px solid #3f7c33; cursor: pointer;">
                                Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Modale de confirmation de suppression --}}
            <div id="delete-product-modal" class="modal-form-backdrop hidden">
                <div class="modal-form-container">
                    <div class="modal-form-header">
                        <h2 class="modal-form-title">Supprimer le produit</h2>
                        <button type="button" id="close-delete-product-btn" class="modal-form-close-button">
                            <img src="{{ asset('images/cross.png') }}" alt="Fermer" style="width: 24px; height: 24px;">
                        </button>
                    </div>
                    <div style="padding: 20px;">
                        <p style="font-family: 'Minecrafter Alt', sans-serif; color: #1b1b18;">
                            Êtes-vous sûr de vouloir supprimer ce produit ?<br>
                            Cette action supprimera également toutes les données associées (commentaires, paniers, commandes liées) ainsi que l'image du produit.
                        </p>
                    </div>
                    <form action="{{ route('produits.destroy', $produit->id_produit) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="modal-form-footer">
                            <button type="button"
                                    id="cancel-delete-product-btn"
                                    class="px-4 py-2"
                                    style="background-color: #e5e7eb; color: #1f2933; font-family: 'Minecrafter Alt', sans-serif; border: 2px solid #9ca3af; cursor: pointer;">
                                Annuler
                            </button>
                            <button type="submit"
                                    class="px-4 py-2"
                                    style="background-color: #b91c1c; color: #ffffff; font-family: 'Minecrafter Alt', sans-serif; border: 2px solid #7f1d1d; cursor: pointer;">
                                Supprimer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        {{-- Section Sélectionné pour vous --}}
        @if(isset($produitsSuggere) && $produitsSuggere->count() > 0)
            <div class="mt-12 pt-12 pb-12" style="padding-left: 50px; padding-right: 50px;">
                <h2 class="text-3xl font-bold text-white mb-6" style="font-family: 'Minecrafter Alt', sans-serif; padding-bottom: 50px; padding-top: 50px; align-items: center; display: flex; justify-content: center; align-content: center; flex-wrap: nowrap; flex-direction: row; text-shadow: -1px -1px 0 #000, 1px -1px 0 #000, -1px 1px 0 #000, 1px 1px 0 #000, 0 0 10px rgba(0, 150, 0, 0.8), 0 0 20px rgba(0, 150, 0, 0.6), 0 0 30px rgba(0, 150, 0, 0.4);">
                    Selectionne pour vous
                </h2>
                <div class="flex flex-row gap-4 justify-center items-stretch">
                    @foreach($produitsSuggere as $produitSug)
                        <div class="flex-1 max-w-[25%]">
                            @include('partials.product-card', [
                                'name' => $produitSug->nom,
                                'description' => $produitSug->description ?? '',
                                'price' => number_format($produitSug->prix, 2, ',', ' '),
                                'image' => $produitSug->image ? asset($produitSug->image) : asset('images/placeholder-product.png'),
                                'productId' => $produitSug->id_produit,
                                'stock' => $produitSug->stock,
                                'infiniteStock' => $produitSug->infinite_stock
                            ])
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const starButtons = document.querySelectorAll('.star-button');
            const noteInput = document.getElementById('note-value');
            const noteError = document.getElementById('note-error');
            let selectedRating = 0;

            starButtons.forEach((button) => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    selectedRating = parseInt(this.getAttribute('data-rating'));
                    noteInput.value = selectedRating;
                    noteError.classList.add('hidden');
                    updateStars(selectedRating);
                });

                // Effet hover
                button.addEventListener('mouseenter', function() {
                    const hoverRating = parseInt(this.getAttribute('data-rating'));
                    updateStars(hoverRating, true);
                });

                button.addEventListener('mouseleave', function() {
                    updateStars(selectedRating);
                });
            });

            function updateStars(rating, isHover = false) {
                starButtons.forEach((button) => {
                    const starRating = parseInt(button.getAttribute('data-rating'));
                    const starImage = button.querySelector('.star-image');
                    // Si on clique sur la 4ème étoile, on remplit les étoiles 1, 2, 3, 4
                    if (starRating <= rating) {
                        starImage.style.opacity = '1.0';
                    } else {
                        starImage.style.opacity = '0.3';
                    }
                });
            }

            // Validation du formulaire
            const form = document.querySelector('form[action*="commentaires"]');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const noteValue = noteInput.value;
                    if (!noteValue || noteValue === '' || noteValue === '0' || parseInt(noteValue) < 1 || parseInt(noteValue) > 5) {
                        e.preventDefault();
                        e.stopPropagation();
                        noteError.classList.remove('hidden');
                        noteError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        return false;
                    }
                    noteError.classList.add('hidden');
                });
            }
            
            // Désactiver le bouton si aucune note n'est sélectionnée
            const submitButton = document.getElementById('submit-comment-btn');
            const btnImage = document.getElementById('btn-image');
            
            function updateSubmitButton() {
                const noteValue = noteInput.value;
                if (submitButton && btnImage) {
                    if (!noteValue || noteValue === '' || noteValue === '0' || parseInt(noteValue) < 1 || parseInt(noteValue) > 5) {
                        submitButton.style.opacity = '0.5';
                        submitButton.style.cursor = 'not-allowed';
                        submitButton.disabled = true;
                        btnImage.style.opacity = '0.5';
                    } else {
                        submitButton.style.opacity = '1';
                        submitButton.style.cursor = 'pointer';
                        submitButton.disabled = false;
                        btnImage.style.opacity = '1';
                    }
                }
            }
            
            // Vérifier au chargement et à chaque changement
            updateSubmitButton();
            noteInput.addEventListener('change', updateSubmitButton);
            
            // Mettre à jour quand une étoile est cliquée
            starButtons.forEach((button) => {
                button.addEventListener('click', function() {
                    setTimeout(updateSubmitButton, 100);
                });
            });
        });
        
        // Gestion du menu déroulant des commentaires
        document.addEventListener('DOMContentLoaded', function() {
            const toggleButton = document.getElementById('toggle-comments');
            const commentsContent = document.getElementById('comments-content');
            const arrowIcon = document.getElementById('arrow-icon');
            
            if (toggleButton && commentsContent && arrowIcon) {
                toggleButton.addEventListener('click', function() {
                    const isHidden = commentsContent.classList.contains('hidden');
                    
                    if (isHidden) {
                        commentsContent.classList.remove('hidden');
                        arrowIcon.style.transform = 'rotate(90deg)';
                    } else {
                        commentsContent.classList.add('hidden');
                        arrowIcon.style.transform = 'rotate(0deg)';
                    }
                });
            }
        });

        // Gestion des modales d'édition et de suppression de produit
        document.addEventListener('DOMContentLoaded', function() {
            const editBtn = document.getElementById('open-edit-product-btn');
            const deleteBtn = document.getElementById('open-delete-product-btn');
            const editModal = document.getElementById('edit-product-modal');
            const deleteModal = document.getElementById('delete-product-modal');
            const closeEditBtn = document.getElementById('close-edit-product-btn');
            const closeDeleteBtn = document.getElementById('close-delete-product-btn');
            const cancelEditBtn = document.getElementById('cancel-edit-product-btn');
            const cancelDeleteBtn = document.getElementById('cancel-delete-product-btn');

            if (editBtn && editModal) {
                editBtn.addEventListener('click', function() {
                    editModal.classList.remove('hidden');
                });
            }

            if (closeEditBtn && editModal) {
                closeEditBtn.addEventListener('click', function() {
                    editModal.classList.add('hidden');
                });
            }

            if (cancelEditBtn && editModal) {
                cancelEditBtn.addEventListener('click', function() {
                    editModal.classList.add('hidden');
                });
            }

            if (editModal) {
                editModal.addEventListener('click', function(e) {
                    if (e.target === editModal) {
                        editModal.classList.add('hidden');
                    }
                });
            }

            if (deleteBtn && deleteModal) {
                deleteBtn.addEventListener('click', function() {
                    deleteModal.classList.remove('hidden');
                });
            }

            if (closeDeleteBtn && deleteModal) {
                closeDeleteBtn.addEventListener('click', function() {
                    deleteModal.classList.add('hidden');
                });
            }

            if (cancelDeleteBtn && deleteModal) {
                cancelDeleteBtn.addEventListener('click', function() {
                    deleteModal.classList.add('hidden');
                });
            }

            if (deleteModal) {
                deleteModal.addEventListener('click', function(e) {
                    if (e.target === deleteModal) {
                        deleteModal.classList.add('hidden');
                    }
                });
            }

            const editInfiniteCheckbox = document.getElementById('edit_infinite_stock');
            const editStockInput = document.getElementById('edit_stock_input');
            const editStockLowThresholdInput = document.getElementById('edit_stock_low_threshold');
            const editStockLowThresholdLabel = document.getElementById('edit_stock_low_threshold_label');
            const editStockLabel = document.getElementById('edit_stock_label');

            function updateEditStockInfiniteState() {
                if (!editInfiniteCheckbox || !editStockInput || !editStockLowThresholdInput) {
                    return;
                }
                if (editInfiniteCheckbox.checked) {
                    editStockInput.value = '';
                    editStockInput.disabled = true;
                    editStockLowThresholdInput.disabled = true;
                    editStockInput.classList.add('modal-form-input-disabled');
                    editStockLowThresholdInput.classList.add('modal-form-input-disabled');
                    if (editStockLabel) {
                        editStockLabel.classList.add('modal-form-label-disabled');
                    }
                    if (editStockLowThresholdLabel) {
                        editStockLowThresholdLabel.classList.add('modal-form-label-disabled');
                    }
                } else {
                    editStockInput.disabled = false;
                    editStockLowThresholdInput.disabled = false;
                    editStockInput.classList.remove('modal-form-input-disabled');
                    editStockLowThresholdInput.classList.remove('modal-form-input-disabled');
                    if (editStockLabel) {
                        editStockLabel.classList.remove('modal-form-label-disabled');
                    }
                    if (editStockLowThresholdLabel) {
                        editStockLowThresholdLabel.classList.remove('modal-form-label-disabled');
                    }
                }
            }

            if (editInfiniteCheckbox) {
                editInfiniteCheckbox.addEventListener('change', updateEditStockInfiniteState);
                updateEditStockInfiniteState();
            }
        });
    </script>
    @endpush
@endsection
