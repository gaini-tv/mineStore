@extends('layouts.app')

@section('title', 'Paiement réussi')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/panier.css') }}">
@endpush

@section('content')
    <div class="panier-banner">
        <img src="{{ asset('images/banierP.png') }}" alt="Paiement" class="panier-banner-image">
        <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
            <h1 class="panier-section-title">
                Paiement réussi
            </h1>
        </div>
    </div>

    <div class="panier-page">
        <div class="panier-grid">
            <section class="panier-section">
                <div class="panier-section-header">
                    <h2 class="panier-section-title" style="font-size: 1.5rem;">Merci pour votre achat</h2>
                </div>
                <p class="panier-text-muted" style="margin-top: 1rem;">
                    Votre paiement a bien été pris en compte. Vos produits seront disponibles prochainement.
                </p>
            </section>
        </div>
    </div>
@endsection

