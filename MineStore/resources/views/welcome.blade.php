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

    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-[#1b1b18] mb-4">Bienvenue sur {{ config('app.name', 'MineStore') }}</h1>
        <p class="text-[#706f6c]">Votre contenu ici...</p>
    </div>

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
