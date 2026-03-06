{{-- Carte produit --}}
<div class="product-card bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 flex flex-col h-full relative min-w-0">
    {{-- Image avec prix en haut à gauche de l'image --}}
    <div class="flex-1 flex items-center justify-center p-3 sm:p-4 md:p-6 bg-gray-50 min-h-0">
        <div class="relative inline-block w-full max-w-full">
            @if(isset($productId))
                <a href="{{ route('produits.show', $productId) }}" class="block cursor-pointer">
                    <img src="{{ $image ?? asset('images/placeholder-product.png') }}"
                         alt="{{ $name ?? 'Produit' }}"
                         class="product-card-img w-full h-36 sm:h-44 md:h-56 lg:h-64 object-contain block transition-all duration-300 hover:scale-110 hover:opacity-90">
                </a>
            @else
                <img src="{{ $image ?? asset('images/placeholder-product.png') }}"
                     alt="{{ $name ?? 'Produit' }}"
                     class="product-card-img w-full h-36 sm:h-44 md:h-56 lg:h-64 object-contain block transition-all duration-300 hover:scale-110 hover:opacity-90 cursor-pointer">
            @endif
            <div class="price-badge absolute top-0 left-0 z-10"
                 style="background-image: url('{{ asset('images/prix.png') }}'); background-size: 100% 100%; background-position: center; background-repeat: no-repeat; display: flex; align-items: center; justify-content: center;">
                <span class="product-card-price text-white font-bold" style="font-family: 'Minecrafter Alt', sans-serif; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);">
                    {{ $price ?? '0,00' }} €
                </span>
            </div>
        </div>
    </div>

    {{-- Informations produit et bouton --}}
    @php
        $stockValue = $stock ?? null;
        $infiniteStock = $infiniteStock ?? false;
        $ruptureMarketing = $ruptureMarketing ?? false;
        $isOutOfStock = $ruptureMarketing || (!$infiniteStock && $stockValue !== null && $stockValue <= 0);
    @endphp

    <div class="p-3 sm:p-4 bg-white bas-card" style="text-align: center;">
        <h3 class="product-card-title font-semibold text-[#1b1b18] mb-2 mt-2 sm:mt-4" style="font-family: 'Minecrafter Alt', sans-serif; color: white; text-shadow: -1px -1px 0 #000, 1px -1px 0 #000, -1px 1px 0 #000, 1px 1px 0 #000, 0 0 10px rgba(0, 150, 0, 0.8), 0 0 20px rgba(0, 150, 0, 0.6), 0 0 30px rgba(0, 150, 0, 0.4);">
            {{ $name ?? 'Nom du produit' }}
        </h3>

        {{-- Bouton Ajouter au panier avec image de fond --}}
        @if(isset($productId))
            <form method="POST" action="{{ route('panier.add', $productId) }}" class="w-full">
                @csrf
                <div class="relative mx-auto btn-panier-wrapper {{ $isOutOfStock ? 'btn-panier-wrapper-disabled' : '' }} product-card-btn-wrapper">
                    <img src="{{ asset('images/btnpanier.png') }}" alt="" class="w-full h-auto block">
                    <button type="submit"
                            class="absolute inset-0 w-full h-full flex items-center justify-center hover:opacity-90 transition-opacity duration-200 btn-panier {{ $isOutOfStock ? 'btn-panier-disabled' : '' }}"
                            style="background: transparent; border: none; padding: 0;{{ $isOutOfStock ? ' cursor: not-allowed;' : ' cursor: pointer;' }}"
                            @if($isOutOfStock) disabled @endif>
                        <span class="product-card-btn-text text-white font-bold" style="font-family: 'Minecrafter Alt', sans-serif; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);">
                            AJOUTER AU PANIER
                        </span>
                    </button>
                </div>
            </form>
        @else
            <div class="relative mx-auto btn-panier-wrapper btn-panier-wrapper-disabled product-card-btn-wrapper">
                <img src="{{ asset('images/btnpanier.png') }}" alt="" class="w-full h-auto block">
                <button type="button"
                        class="absolute inset-0 w-full h-full flex items-center justify-center hover:opacity-90 transition-opacity duration-200 btn-panier btn-panier-disabled"
                        style="background: transparent; border: none; padding: 0; cursor: not-allowed;"
                        disabled>
                    <span class="product-card-btn-text text-white font-bold" style="font-family: 'Minecrafter Alt', sans-serif; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);">
                        AJOUTER AU PANIER
                    </span>
                </button>
            </div>
        @endif
    </div>
</div>
