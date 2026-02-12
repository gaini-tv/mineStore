@extends('layouts.app')

@section('title', $produit->nom)

@section('content')
    <div class="container mx-auto px-4 py-8" style="padding-top: 200px;">
        <div class="bg-white rounded-lg shadow-md overflow-hidden" style="width: 100%;">
            <div class="flex flex-col md:flex-row">
                {{-- Image du produit --}}
                <div class="md:w-1/2 flex items-center justify-center p-8 bg-gray-50" style="width: 50%;">
                    <img src="{{ $produit->image ? asset($produit->image) : asset('images/placeholder-product.png') }}"
                         alt="{{ $produit->nom }}"
                         class="max-w-full max-h-96 object-contain transition-all duration-300 hover:scale-105 hover:opacity-90 cursor-pointer">
                </div>

                {{-- Informations du produit --}}
                <div class="md:w-1/2 p-8 flex flex-col justify-center relative" style="width: 50%; display: flex; flex-direction: column; flex-wrap: nowrap; align-content: center; justify-content: center; align-items: flex-start; padding-right: 100px; row-gap: 30px;">
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
                        @if($produit->stock > 50)
                            <span class="font-semibold absolute right-0" style="color: #5baa47; font-family: 'Minecrafter Alt', sans-serif;">En stock</span>
                        @elseif($produit->stock > 0)
                            <span class="font-semibold absolute right-0" style="color: #ff9800; font-family: 'Minecrafter Alt', sans-serif;">Stock faible</span>
                        @else
                            <span class="font-semibold absolute right-0" style="color: #f44336; font-family: 'Minecrafter Alt', sans-serif;">Rupture de stock</span>
                        @endif
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

                    {{-- Bouton Ajouter au panier avec image PEGI --}}
                    <div class="relative mb-4" style="position: relative; width: 100%;">
                        <div class="relative" style="display: inline-block; width: 300px;">
                            <img src="{{ asset('images/btnpanier.png') }}" alt="" class="w-full h-auto block">
                            <button type="button"
                                    class="absolute inset-0 w-full h-full flex items-center justify-center hover:opacity-90 transition-opacity duration-200"
                                    style="background: transparent; border: none; cursor: pointer; padding: 0;">
                                <span class="text-white font-bold text-sm md:text-base" style="font-family: 'Minecrafter Alt', sans-serif; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);">
                                    AJOUTER AU PANIER
                                </span>
                            </button>
                        </div>
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

            {{-- Formulaire d'ajout de commentaire --}}
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
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
                    <p class="text-red-500 text-sm mt-1 hidden" id="note-error">Veuillez sélectionner une note</p>

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
                    <button type="submit" 
                            class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors duration-200"
                            style="font-family: 'Minecrafter Alt', sans-serif;">
                        Publier le commentaire
                    </button>
                </form>
            </div>

            {{-- Liste des commentaires --}}
            @if(isset($commentaires) && $commentaires->count() > 0)
                <div class="space-y-6">
                    @foreach($commentaires as $commentaire)
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <div class="flex items-start justify-between mb-3">
                                <div>
                                    <h4 class="text-lg font-bold" style="font-family: 'Minecrafter Alt', sans-serif; color: #1b1b18;">
                                        {{ $commentaire->user->name ?? 'Utilisateur anonyme' }}
                                    </h4>
                                    <p class="text-sm text-gray-500">
                                        {{ $commentaire->date_->format('d/m/Y à H:i') }}
                                    </p>
                                </div>
                                {{-- Affichage des étoiles --}}
                                <div class="flex gap-1 items-center">
                                    @for($i = 1; $i <= 5; $i++)
                                        <img src="https://minecraft.wiki/images/Nether_Star.gif?fb01f&format=original" 
                                             alt="Étoile" 
                                             style="opacity: {{ $i <= ($commentaire->note ?? 0) ? '1.0' : '0.3' }}; width: 40px; height: 40px;">
                                    @endfor
                                </div>
                            </div>
                            <p class="text-gray-700" style="text-align: justify;">
                                {{ $commentaire->contenu }}
                            </p>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <p class="text-gray-500" style="font-family: 'Minecrafter Alt', sans-serif;">
                        Aucun commentaire pour le moment. Soyez le premier à commenter !
                    </p>
                </div>
            @endif
        </div>

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
                                'productId' => $produitSug->id_produit
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
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    if (!noteInput.value || noteInput.value === '') {
                        e.preventDefault();
                        noteError.classList.remove('hidden');
                        return false;
                    }
                });
            }
        });
    </script>
    @endpush
@endsection
