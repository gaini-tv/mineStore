@extends('layouts.app')

@section('title', 'Livraison - MineStore')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/shipping.css') }}">
@endpush

@section('content')
<div class="page-container">
    <div class="page-header">
        <h1>Livraison</h1>
        <p class="subtitle">Tout ce que vous devez savoir sur l'expédition de vos trésors</p>
    </div>

    <div class="shipping-content">
        <section class="shipping-section">
            <h2>Modes de Livraison</h2>
            <div class="shipping-options">
                <div class="option-card">
                    <div class="icon">🐢</div>
                    <h3>Livraison Standard</h3>
                    <p>Délai : 3-5 jours ouvrés</p>
                    <p class="price">4.99€ (Gratuit dès 50€)</p>
                    <p class="desc">Livraison via Colissimo ou Mondial Relay.</p>
                </div>
                <div class="option-card">
                    <div class="icon">🐇</div>
                    <h3>Livraison Express</h3>
                    <p>Délai : 24-48h</p>
                    <p class="price">9.99€</p>
                    <p class="desc">Livraison via Chronopost pour les plus pressés.</p>
                </div>
                <div class="option-card">
                    <div class="icon">🐉</div>
                    <h3>Livraison Ender</h3>
                    <p>Délai : Instantané</p>
                    <p class="price">Gratuit</p>
                    <p class="desc">Pour tous les produits digitaux (e-books, mods, textures).</p>
                </div>
            </div>
        </section>

        <section class="shipping-section">
            <h2>Zones de Livraison</h2>
            <p>Nous livrons actuellement en France Métropolitaine, Belgique, Suisse et Luxembourg. Pour les autres destinations, veuillez nous contacter.</p>
        </section>

        <section class="shipping-section">
            <h2>Suivi de Commande</h2>
            <p>Dès l'expédition de votre commande, vous recevrez un email contenant un numéro de suivi. Vous pourrez ainsi suivre l'acheminement de votre colis en temps réel depuis votre espace client ou sur le site du transporteur.</p>
        </section>
    </div>
</div>
@endsection
