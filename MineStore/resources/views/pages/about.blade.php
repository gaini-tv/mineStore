@extends('layouts.app')

@section('title', 'Qui sommes-nous ? - MineStore')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/about.css') }}">
@endpush

@section('content')
<div class="page-container">
    <div class="page-header">
        <h1>Qui sommes-nous ?</h1>
        <p class="subtitle">Votre destination ultime pour l'univers Minecraft</p>
    </div>

    <div class="page-content">
        <section class="about-section">
            <div class="about-text">
                <h2>Notre Mission</h2>
                <p>Chez MineStore, nous sommes passionnés par l'univers infini de Minecraft. Notre mission est simple : fournir aux joueurs, créateurs et fans les meilleurs produits, guides et ressources pour enrichir leur expérience de jeu.</p>
                <p>Que vous soyez un bâtisseur chevronné, un explorateur intrépide ou un guerrier redoutable, nous avons ce qu'il vous faut pour pousser votre créativité et vos compétences au niveau supérieur.</p>
            </div>
            <div class="about-image">
                <img src="{{ asset('images/steve.png') }}" alt="Steve Minecraft" onerror="this.src='https://placehold.co/400x300?text=MineStore'">
            </div>
        </section>

        <section class="about-values">
            <h2>Nos Valeurs</h2>
            <div class="values-grid">
                <div class="value-card">
                    <div class="value-icon">💎</div>
                    <h3>Qualité</h3>
                    <p>Nous sélectionnons rigoureusement chaque produit pour garantir la meilleure expérience possible.</p>
                </div>
                <div class="value-card">
                    <div class="value-icon">🌍</div>
                    <h3>Communauté</h3>
                    <p>Nous croyons en la force de la communauté Minecraft et soutenons les créateurs indépendants.</p>
                </div>
                <div class="value-card">
                    <div class="value-icon">🚀</div>
                    <h3>Innovation</h3>
                    <p>Nous sommes toujours à l'affût des dernières nouveautés et tendances du monde cubique.</p>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
