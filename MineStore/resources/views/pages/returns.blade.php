@extends('layouts.app')

@section('title', 'Politique de Retours - MineStore')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/returns.css') }}">
@endpush

@section('content')
<div class="page-container">
    <div class="page-header">
        <h1>Politique de Retours</h1>
        <p class="subtitle">Satisfait ou remboursé</p>
    </div>

    <div class="returns-content">
        <section class="policy-section">
            <h2>Délai de rétractation</h2>
            <p>Conformément à la législation en vigueur, vous disposez d'un délai de 14 jours à compter de la réception de vos produits pour exercer votre droit de rétractation sans avoir à justifier de motifs ni à payer de pénalité.</p>
        </section>

        <section class="policy-section">
            <h2>Conditions de retour</h2>
            <ul>
                <li>Les produits doivent être retournés dans leur emballage d'origine et en parfait état.</li>
                <li>Les produits incomplets, abîmés, endommagés ou salis ne seront pas repris.</li>
                <li>Les produits digitaux (clés CD, e-books) ne sont pas remboursables une fois le code dévoilé ou le téléchargement effectué.</li>
            </ul>
        </section>

        <section class="policy-section">
            <h2>Procédure de retour</h2>
            <ol>
                <li>Connectez-vous à votre compte et allez dans "Mes commandes".</li>
                <li>Sélectionnez la commande concernée et cliquez sur "Effectuer un retour".</li>
                <li>Imprimez le bon de retour et glissez-le dans votre colis.</li>
                <li>Envoyez le colis à l'adresse indiquée sur le bon.</li>
            </ol>
        </section>

        <section class="policy-section">
            <h2>Remboursement</h2>
            <p>Le remboursement sera effectué dans un délai de 14 jours suivant la réception de votre retour, via le même moyen de paiement que celui utilisé lors de la commande.</p>
        </section>
    </div>
</div>
@endsection
