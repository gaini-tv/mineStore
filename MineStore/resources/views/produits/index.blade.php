@extends('layouts.app')

@section('title', 'Nos produits')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-[#1b1b18] mb-6">Nos produits</h1>
        
        @if($produits->isEmpty())
            <p class="text-[#706f6c]">Aucun produit disponible pour le moment.</p>
        @else
            {{-- Grille de produits --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($produits as $produit)
                    @include('partials.product-card', [
                        'name' => $produit->nom,
                        'description' => $produit->description ?? '',
                        'price' => number_format($produit->prix, 2, ',', ' '),
                        'image' => $produit->image ? asset($produit->image) : asset('images/placeholder-product.png')
                    ])
                @endforeach
            </div>
        @endif
    </div>
@endsection
