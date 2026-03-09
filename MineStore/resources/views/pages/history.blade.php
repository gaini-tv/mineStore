@extends('layouts.app')

@section('title', 'Notre Histoire - MineStore')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/history.css') }}">
@endpush

@section('content')
<div class="page-container">
    <div class="page-header">
        <h1>Notre Histoire</h1>
        <p class="subtitle">De la première pierre posée à l'empire du cube</p>
    </div>

    <div class="timeline">
        <div class="timeline-item left">
            <div class="content">
                <h2>2020</h2>
                <p>L'idée de MineStore germe dans l'esprit de notre fondateur, Clément, alors qu'il cherchait désespérément un guide complet pour survivre sa première nuit en mode Hardcore.</p>
            </div>
        </div>
        <div class="timeline-item right">
            <div class="content">
                <h2>2021</h2>
                <p>Lancement de la première version du site. Une simple page HTML proposant quelques guides PDF. Le succès est immédiat auprès de la communauté francophone.</p>
            </div>
        </div>
        <div class="timeline-item left">
            <div class="content">
                <h2>2022</h2>
                <p>MineStore s'agrandit ! Nous accueillons nos premiers partenaires et commençons à proposer des produits dérivés officiels et des créations de fans.</p>
            </div>
        </div>
        <div class="timeline-item right">
            <div class="content">
                <h2>2023</h2>
                <p>Lancement de la plateforme marketplace "Entreprise", permettant aux créateurs de vendre leurs propres contenus et mods directement sur MineStore.</p>
            </div>
        </div>
        <div class="timeline-item left">
            <div class="content">
                <h2>Aujourd'hui</h2>
                <p>MineStore est devenu la référence incontournable pour tout ce qui touche à Minecraft. Et ce n'est que le début de l'aventure !</p>
            </div>
        </div>
    </div>
</div>
@endsection
