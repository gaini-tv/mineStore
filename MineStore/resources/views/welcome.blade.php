@extends('layouts.app')

@section('title', 'Accueil')

@section('content')
    {{-- Image avec barre de recherche au centre --}}
    <div class="w-full relative">
        <img src="{{ asset('images/bgnav.png') }}" alt="Background" class="w-full h-auto">
        <div class="absolute inset-0 flex items-center justify-center px-4 md:px-6">
            <form action="{{ route('search.produits') }}" method="GET" class="w-full max-w-4xl">
                <div class="searchbar-container flex gap-2 p-3 md:p-4" style="background-image: url('{{ asset('images/searchbar.png') }}'); background-size: 100% 100%; background-position: center; background-repeat: no-repeat; border: none; border-radius: 0;">
                    <input type="text"
                           name="q"
                           value="{{ old('q') }}"
                           id="search-input"
                           placeholder=""
                           class="flex-1 px-3 py-2 md:px-5 md:py-4 border-0 bg-transparent text-white placeholder-[#706f6c] focus:outline-none text-sm md:text-lg"
                           style="font-family: 'Minecrafter Alt', sans-serif; border-radius: 0; border: none; color: white;">
                    <button type="submit"
                            class="p-2 pr-6 md:p-3 md:pr-12 bg-transparent hover:opacity-80 transition-opacity flex items-center justify-center"
                            style="border-radius: 0; border: none; cursor: pointer;">
                        <img src="{{ asset('images/iconrecherce.png') }}" alt="Rechercher" class="h-8 w-8 md:h-12 md:w-12">
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Section Nouveautés --}}
    @if(isset($nouveautes) && $nouveautes->count() > 0)
        <div class="container mx-auto px-4 py-8">
            <div class="mb-6" style="padding: 29px; display: flex; flex-direction: row; flex-wrap: wrap; justify-content: space-between; align-items: center;">
                <h2 class="text-3xl font-bold text-white" style="font-family: 'Minecrafter Alt', sans-serif; text-shadow: -1px -1px 0 #000, 1px -1px 0 #000, -1px 1px 0 #000, 1px 1px 0 #000, 0 0 10px rgba(0, 150, 0, 0.8), 0 0 20px rgba(0, 150, 0, 0.6), 0 0 30px rgba(0, 150, 0, 0.4);">Nos nouveautes</h2>
                <a href="{{ route('produits.index') }}" class="hover:opacity-80 transition-opacity">
                    <img src="{{ asset('images/plus.png') }}" alt="Voir tous les produits" class="h-16 w-16">
                </a>
            </div>
            <div class="Allproduits grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                @foreach($nouveautes as $produit)
                    @include('partials.product-card', [
                        'name' => $produit->nom,
                        'description' => $produit->description ?? '',
                        'price' => number_format($produit->prix, 2, ',', ' '),
                        'image' => $produit->image ? asset($produit->image) : asset('images/placeholder-product.png'),
                        'productId' => $produit->id_produit
                    ])
                @endforeach
            </div>
        </div>
    @endif

    {{-- Section Nos jeux --}}
    @if(isset($jeux) && $jeux->count() > 0)
        <div class="container mx-auto px-4 py-8" id="jeux-section" style="padding-top: 10px; padding-bottom: 10px; padding-left: 100px; padding-right: 100px; min-height: 600px;">
            <div class="mb-6" style="padding: 65px; display: flex; flex-direction: row; flex-wrap: wrap; justify-content: center; align-items: center;">
                <h2 class="text-3xl font-bold text-white" style="font-family: 'Minecrafter Alt', sans-serif; text-shadow: -1px -1px 0 #000, 1px -1px 0 #000, -1px 1px 0 #000, 1px 1px 0 #000, 0 0 10px rgba(0, 150, 0, 0.8), 0 0 20px rgba(0, 150, 0, 0.6), 0 0 30px rgba(0, 150, 0, 0.4);">Nos jeux</h2>
            </div>
            <div class="bg-white rounded-lg shadow-md overflow-hidden flex flex-col md:flex-row items-center gap-6 p-6 relative" style="min-height: 500px; max-width: 100%;">
                {{-- Contenu à gauche --}}
                <div class="flex-1 flex flex-col gap-4" id="jeu-content" style="opacity: 1; transform: translateY(0); display: flex; flex-direction: column; flex-wrap: nowrap; align-content: center; justify-content: center; align-items: center; row-gap: 40px; max-width: 50%; transition: opacity 0.6s cubic-bezier(0.4, 0, 0.2, 1), transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);">
                    <h3 class="text-2xl md:text-3xl font-bold text-[#1b1b18]" style="font-family: 'Minecrafter Alt', sans-serif; opacity: 1; transition: opacity 0.6s cubic-bezier(0.4, 0, 0.2, 1);" id="jeu-titre">{{ $jeux[0]->nom }}</h3>
                    <p class="text-[#706f6c] text-base md:text-lg" style="opacity: 1; transition: opacity 0.6s cubic-bezier(0.4, 0, 0.2, 1);" id="jeu-description">{{ $jeux[0]->description }}</p>
                    <div class="flex flex-col sm:flex-row gap-4 mt-4" style="display: flex; flex-direction: column; flex-wrap: nowrap; align-content: center; justify-content: center; align-items: center; row-gap: 20px;">
                        {{-- Bouton Ajouter au panier --}}
                        <div class="relative" style="display: inline-block; width: 200px;">
                            <img src="{{ asset('images/btnpanier.png') }}" alt="" class="w-full h-auto block">
                            <button type="button"
                                    class="absolute inset-0 w-full h-full flex items-center justify-center hover:opacity-90 transition-opacity duration-200"
                                    style="background: transparent; border: none; cursor: pointer; padding: 0;">
                                <span class="text-white font-bold text-xs md:text-sm" style="font-family: 'Minecrafter Alt', sans-serif; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);">
                                    AJOUTER AU PANIER
                                </span>
                            </button>
                        </div>
                        {{-- Bouton En savoir plus --}}
                        <div class="relative" style="display: inline-block; width: 200px;">
                            <img src="{{ asset('images/btnESP.png') }}" alt="" class="w-full h-auto block">
                            <a href="{{ route('produits.show', $jeux[0]->id_produit) }}" id="btn-en-savoir-plus"
                               class="absolute inset-0 w-full h-full flex items-center justify-center hover:opacity-90 transition-opacity duration-200"
                               style="background: transparent; border: none; cursor: pointer; padding: 0; text-decoration: none;">
                                <span class="text-white font-bold text-xs md:text-sm" style="font-family: 'Minecrafter Alt', sans-serif; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);">
                                    EN SAVOIR PLUS
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
                {{-- Images empilées à droite --}}
                <div class="flex-shrink-0 w-full md:w-1/3 lg:w-1/4 relative" style="min-height: 800px; max-width: 50%;">
                    {{-- Carte du fond (cliquable) - Image du jeu suivant - À droite, décalée vers le bas, 60% de la taille --}}
                    <div class="absolute cursor-pointer transition-all duration-300 hover:scale-105 flex items-start justify-start" 
                         style="left: 50%; top: 15%; z-index: 1;"
                         id="carte-fond"
                         data-jeu-index="{{ $jeux->count() > 1 ? 1 : 0 }}">
                        @if($jeux->count() > 1)
                            <img src="{{ $jeux[1]->image ? asset($jeux[1]->image) : asset('images/placeholder-product.png') }}"
                                 alt="{{ $jeux[1]->nom }}"
                                 style="width: auto; height: auto; object-fit: contain; transform: scale(0.6); transform-origin: top left; opacity: 1; transition: opacity 0.5s cubic-bezier(0.4, 0, 0.2, 1), transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);"
                                 id="img-carte-fond">
                        @endif
                    </div>
                    {{-- Carte du milieu (cliquable) - Image du 3ème jeu - Au milieu, légèrement décalée, 80% de la taille --}}
                    <div class="absolute cursor-pointer transition-all duration-300 hover:scale-105 flex items-start justify-start" 
                         style="left: 30%; top: 8%; z-index: 2;"
                         id="carte-milieu"
                         data-jeu-index="{{ $jeux->count() > 2 ? 2 : ($jeux->count() > 1 ? 1 : 0) }}">
                        @if($jeux->count() > 2)
                            <img src="{{ $jeux[2]->image ? asset($jeux[2]->image) : asset('images/placeholder-product.png') }}"
                                 alt="{{ $jeux[2]->nom }}"
                                 style="width: auto; height: auto; object-fit: contain; transform: scale(0.8); transform-origin: top left; opacity: 1; transition: opacity 0.5s cubic-bezier(0.4, 0, 0.2, 1), transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);"
                                 id="img-carte-milieu">
                        @elseif($jeux->count() > 1)
                            <img src="{{ $jeux[1]->image ? asset($jeux[1]->image) : asset('images/placeholder-product.png') }}"
                                 alt="{{ $jeux[1]->nom }}"
                                 style="width: auto; height: auto; object-fit: contain; transform: scale(0.8); transform-origin: top left; opacity: 1; transition: opacity 0.5s cubic-bezier(0.4, 0, 0.2, 1), transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);"
                                 id="img-carte-milieu">
                        @endif
                    </div>
                    {{-- Carte du devant avec image du jeu actuel - À gauche, taille originale (100%), au-dessus --}}
                    <div class="absolute transition-all duration-500 flex items-start justify-start" 
                         style="left: 0; top: 0; z-index: 3;"
                         id="carte-devant">
                        <img src="{{ $jeux[0]->image ? asset($jeux[0]->image) : asset('images/placeholder-product.png') }}"
                             alt="{{ $jeux[0]->nom }}"
                             id="jeu-image"
                             style="opacity: 1; transform: scale(1); width: auto; height: auto; max-width: none; max-height: none; object-fit: contain; transition: opacity 0.6s cubic-bezier(0.4, 0, 0.2, 1), transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);">
                    </div>
                </div>
            </div>
        </div>

        {{-- Données des jeux pour JavaScript --}}
        @php
            $jeuxArray = [];
            foreach($jeux as $jeu) {
                $jeuxArray[] = [
                    'id' => $jeu->id_produit,
                    'nom' => $jeu->nom,
                    'description' => $jeu->description,
                    'image' => $jeu->image ? asset($jeu->image) : asset('images/placeholder-product.png'),
                    'prix' => number_format($jeu->prix, 2, ',', ' ')
                ];
            }
        @endphp
        <script>
            const jeuxData = @json($jeuxArray);
            
            let currentJeuIndex = 0;
            
            function updateCartes() {
                // Mettre à jour les images des cartes en fonction du jeu actuel avec transition fluide
                const carteFond = document.getElementById('carte-fond');
                const carteMilieu = document.getElementById('carte-milieu');
                
                if (jeuxData.length > 1) {
                    // Carte du fond : jeu suivant
                    const indexFond = (currentJeuIndex + 1) % jeuxData.length;
                    if (carteFond) {
                        const imgFond = document.getElementById('img-carte-fond');
                        if (imgFond) {
                            // Fade out
                            imgFond.style.opacity = '0';
                            setTimeout(() => {
                                imgFond.src = jeuxData[indexFond].image;
                                imgFond.alt = jeuxData[indexFond].nom;
                                // Fade in
                                setTimeout(() => {
                                    imgFond.style.opacity = '1';
                                }, 50);
                            }, 200);
                        }
                        carteFond.setAttribute('data-jeu-index', indexFond);
                    }
                    
                    // Carte du milieu : jeu suivant après
                    if (jeuxData.length > 2) {
                        const indexMilieu = (currentJeuIndex + 2) % jeuxData.length;
                        if (carteMilieu) {
                            const imgMilieu = document.getElementById('img-carte-milieu');
                            if (imgMilieu) {
                                // Fade out
                                imgMilieu.style.opacity = '0';
                                setTimeout(() => {
                                    imgMilieu.src = jeuxData[indexMilieu].image;
                                    imgMilieu.alt = jeuxData[indexMilieu].nom;
                                    // Fade in
                                    setTimeout(() => {
                                        imgMilieu.style.opacity = '1';
                                    }, 50);
                                }, 200);
                            }
                            carteMilieu.setAttribute('data-jeu-index', indexMilieu);
                        }
                    } else if (jeuxData.length === 2) {
                        // Si seulement 2 jeux, la carte du milieu affiche le même que le fond
                        if (carteMilieu) {
                            const imgMilieu = document.getElementById('img-carte-milieu');
                            if (imgMilieu) {
                                // Fade out
                                imgMilieu.style.opacity = '0';
                                setTimeout(() => {
                                    imgMilieu.src = jeuxData[indexFond].image;
                                    imgMilieu.alt = jeuxData[indexFond].nom;
                                    // Fade in
                                    setTimeout(() => {
                                        imgMilieu.style.opacity = '1';
                                    }, 50);
                                }, 200);
                            }
                            carteMilieu.setAttribute('data-jeu-index', indexFond);
                        }
                    }
                }
            }
            
            function changerJeu(nouvelIndex) {
                // Animation de fade out
                const content = document.getElementById('jeu-content');
                const image = document.getElementById('jeu-image');
                const titre = document.getElementById('jeu-titre');
                const description = document.getElementById('jeu-description');
                
                if (content && image && nouvelIndex !== currentJeuIndex) {
                    // Désactiver les transitions pendant le changement pour éviter les effets indésirables
                    content.style.transition = 'opacity 0.4s cubic-bezier(0.4, 0, 0.2, 1), transform 0.4s cubic-bezier(0.4, 0, 0.2, 1)';
                    image.style.transition = 'opacity 0.4s cubic-bezier(0.4, 0, 0.2, 1), transform 0.4s cubic-bezier(0.4, 0, 0.2, 1)';
                    
                    // Fade out avec translation
                    content.style.opacity = '0';
                    content.style.transform = 'translateY(-15px)';
                    image.style.opacity = '0';
                    image.style.transform = 'scale(0.95) translateY(10px)';
                    if (titre) titre.style.opacity = '0';
                    if (description) description.style.opacity = '0';
                    
                    setTimeout(() => {
                        // Changer le jeu
                        currentJeuIndex = nouvelIndex;
                        const jeu = jeuxData[currentJeuIndex];
                        
                        // Mettre à jour le contenu
                        if (titre) titre.textContent = jeu.nom;
                        if (description) description.textContent = jeu.description;
                        image.src = jeu.image;
                        image.alt = jeu.nom;
                        
                        // Mettre à jour le lien "En savoir plus"
                        const btnEnSavoirPlus = document.getElementById('btn-en-savoir-plus');
                        if (btnEnSavoirPlus && jeu.id) {
                            btnEnSavoirPlus.href = '/produits/' + jeu.id;
                        }
                        
                        // Mettre à jour les cartes avec transition
                        updateCartes();
                        
                        // Réinitialiser les positions pour le fade in
                        content.style.transform = 'translateY(15px)';
                        image.style.transform = 'scale(0.95) translateY(-10px)';
                        
                        // Réactiver les transitions pour le fade in
                        setTimeout(() => {
                            content.style.transition = 'opacity 0.6s cubic-bezier(0.4, 0, 0.2, 1), transform 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
                            image.style.transition = 'opacity 0.6s cubic-bezier(0.4, 0, 0.2, 1), transform 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
                            
                            // Fade in avec animation fluide
                            content.style.opacity = '1';
                            content.style.transform = 'translateY(0)';
                            image.style.opacity = '1';
                            image.style.transform = 'scale(1) translateY(0)';
                            if (titre) titre.style.opacity = '1';
                            if (description) description.style.opacity = '1';
                        }, 10);
                    }, 400);
                }
            }
            
            document.addEventListener('DOMContentLoaded', function() {
                const carteFond = document.getElementById('carte-fond');
                const carteMilieu = document.getElementById('carte-milieu');
                
                if (carteFond) {
                    carteFond.addEventListener('click', function() {
                        const index = parseInt(this.getAttribute('data-jeu-index'));
                        changerJeu(index);
                    });
                }
                
                if (carteMilieu) {
                    carteMilieu.addEventListener('click', function() {
                        const index = parseInt(this.getAttribute('data-jeu-index'));
                        changerJeu(index);
                    });
                }
                
                // Initialiser les cartes
                updateCartes();
            });
        </script>
    @endif

    <script>
        (function() {
            const searchInput = document.getElementById('search-input');
            if (!searchInput) return;

            const placeholders = [
                'serveurs...',
                'jeux...',
                'textiles...',
                'livres...',
                'peluches...',
                'figurines...',
                'autre...',
                'rechercher un produit ...'
            ];

            let currentPlaceholder = '';
            let currentIndex = 0;
            let isDeleting = false;
            let placeholderIndex = 0;
            let typingSpeed = 100; // Vitesse de frappe (ms)
            let deletingSpeed = 50; // Vitesse de suppression (ms)
            let pauseTime = 2000; // Temps de pause après avoir terminé un mot (ms)

            function getRandomPlaceholder() {
                // Sélectionner un placeholder aléatoire différent du précédent
                let newIndex;
                do {
                    newIndex = Math.floor(Math.random() * placeholders.length);
                } while (newIndex === placeholderIndex && placeholders.length > 1);
                placeholderIndex = newIndex;
                return placeholders[placeholderIndex];
            }

            function typePlaceholder() {
                const targetPlaceholder = getRandomPlaceholder();
                currentPlaceholder = targetPlaceholder;
                currentIndex = 0;
                isDeleting = false;
                type();
            }

            function type() {
                if (!isDeleting && currentIndex < currentPlaceholder.length) {
                    // Typing
                    searchInput.setAttribute('placeholder', currentPlaceholder.substring(0, currentIndex + 1));
                    currentIndex++;
                    setTimeout(type, typingSpeed);
                } else if (!isDeleting && currentIndex === currentPlaceholder.length) {
                    // Pause après avoir terminé de taper
                    setTimeout(() => {
                        isDeleting = true;
                        type();
                    }, pauseTime);
                } else if (isDeleting && currentIndex > 0) {
                    // Deleting
                    currentIndex--;
                    searchInput.setAttribute('placeholder', currentPlaceholder.substring(0, currentIndex));
                    setTimeout(type, deletingSpeed);
                } else {
                    // Commencer un nouveau mot
                    setTimeout(typePlaceholder, 500);
                }
            }

            // Démarrer l'animation seulement si l'input n'a pas de focus et est vide
            function startAnimation() {
                if (!searchInput.value && document.activeElement !== searchInput) {
                    typePlaceholder();
                }
            }

            // Arrêter l'animation quand l'utilisateur commence à taper
            searchInput.addEventListener('focus', function() {
                searchInput.setAttribute('placeholder', '');
                currentIndex = 0;
                isDeleting = false;
            });

            // Reprendre l'animation quand l'input perd le focus et est vide
            searchInput.addEventListener('blur', function() {
                if (!searchInput.value) {
                    setTimeout(startAnimation, 500);
                }
            });

            // Démarrer l'animation au chargement de la page
            startAnimation();
        })();
    </script>
@endsection
