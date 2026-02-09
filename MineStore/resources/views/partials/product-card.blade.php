{{-- Carte produit --}}
<div class="product-card bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 flex flex-col h-full relative">
    {{-- Prix en haut à gauche avec image de fond --}}
    <div class="relative p-4">
        <div class="price-badge absolute top-4 left-4 z-10"
             style="background-image: url('{{ asset('images/prix.png') }}'); background-size: 100% 100%; background-position: center; background-repeat: no-repeat; min-width: 100px; min-height: 45px; display: flex; align-items: center; justify-content: center; padding: 8px 20px;">
            <span class="text-white font-bold text-base md:text-lg" style="font-family: 'Minecrafter Alt', sans-serif; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);">
                {{ $price ?? '0,00' }} €
            </span>
        </div>
    </div>

    {{-- Image au centre --}}
    <div class="flex-1 flex items-center justify-center p-6 bg-gray-50">
        <img src="{{ $image ?? asset('images/placeholder-product.png') }}"
             alt="{{ $name ?? 'Produit' }}"
             class="max-w-full max-h-64 object-contain">
    </div>

    {{-- Informations produit et bouton --}}
    <div class="p-4 bg-white bas-card">
        <h3 class="text-lg font-semibold text-[#1b1b18] mb-2">{{ $name ?? 'Nom du produit' }}</h3>

        {{-- Bouton Ajouter au panier avec image de fond --}}
        <div class="relative mx-auto" style="display: inline-block; width: 33.33%;">
            <img src="{{ asset('images/btnpanier.png') }}" alt="" class="w-full h-auto block" style="display: block;">
            <button type="button"
                    class="absolute inset-0 w-full h-full flex items-center justify-center hover:opacity-90 transition-opacity duration-200"
                    style="background: transparent; border: none; cursor: pointer; padding: 0;">
                <span class="text-white font-bold text-xs md:text-sm" style="font-family: 'Minecrafter Alt', sans-serif; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);">
                    AJOUTER AU PANIER
                </span>
            </button>
        </div>
    </div>
</div>
