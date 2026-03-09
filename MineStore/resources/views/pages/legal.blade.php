@extends('layouts.app')

@section('title', 'Mentions Légales - MineStore')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/legal.css') }}">
@endpush

@section('content')
<div class="page-container">
    <div class="page-header">
        <h1>Mentions Légales</h1>
        <p class="subtitle">Informations légales et juridiques</p>
    </div>

    <div class="legal-content">
        <section class="legal-section">
            <h2>Éditeur du site</h2>
            <p>
                <strong>MineStore SARL</strong><br>
                Capital social : 10 000 €<br>
                Siège social : 123 Avenue du Creeper, 75000 Minecraft City<br>
                RCS Minecraft City B 123 456 789<br>
                N° TVA Intracommunautaire : FR 12 345678900<br>
                Directeur de la publication : Steve Mine
            </p>
        </section>

        <section class="legal-section">
            <h2>Hébergement</h2>
            <p>
                Le site est hébergé par <strong>CloudBlock SAS</strong><br>
                Adresse : 42 Rue du Serveur, 75000 Paris<br>
                Téléphone : 01 99 99 99 99
            </p>
        </section>

        <section class="legal-section">
            <h2>Propriété intellectuelle</h2>
            <p>L'ensemble de ce site relève de la législation française et internationale sur le droit d'auteur et la propriété intellectuelle. Tous les droits de reproduction sont réservés, y compris pour les documents téléchargeables et les représentations iconographiques et photographiques.</p>
            <p>Minecraft est une marque déposée de Mojang Synergies AB. MineStore n'est pas affilié à Mojang Synergies AB.</p>
        </section>

        <section class="legal-section">
            <h2>Données personnelles</h2>
            <p>Conformément à la loi informatique et libertés du 6 janvier 1978, vous disposez d'un droit d'accès et de rectification aux données personnelles vous concernant.</p>
        </section>
    </div>
</div>
@endsection
