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
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

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
                                                    {{ $commentaire->user->name ?? 'Utilisateur anonyme' }}
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
                                        <div class="flex gap-1 items-center ml-4">
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
                        <div class="px-6 pb-6 text-center">
                            <p class="text-gray-500" style="font-family: 'Minecrafter Alt', sans-serif;">
                                Aucun commentaire pour le moment. Soyez le premier à commenter !
                            </p>
                        </div>
                    @endif
                </div>
            </div>
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
    </script>
    @endpush
@endsection
