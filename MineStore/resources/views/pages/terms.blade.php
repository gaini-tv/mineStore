@extends('layouts.app')

@section('title', 'Conditions Générales de Vente - MineStore')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/terms.css') }}">
@endpush

@section('content')
<div class="page-container">
    <div class="page-header">
        <h1>Conditions Générales de Vente</h1>
        <p class="subtitle">CGV applicables au 01/01/2026</p>
    </div>

    <div class="terms-content">
        <section class="term-section">
            <h2>Article 1 : Objet</h2>
            <p>Les présentes conditions régissent les ventes par la société MineStore de produits liés à l'univers Minecraft.</p>
        </section>

        <section class="term-section">
            <h2>Article 2 : Prix</h2>
            <p>Les prix de nos produits sont indiqués en euros toutes taxes comprises (TTC), sauf indication contraire et hors frais de traitement et d'expédition.</p>
        </section>

        <section class="term-section">
            <h2>Article 3 : Commandes</h2>
            <p>Vous pouvez passer commande sur Internet via notre site www.minestore.fr. Les informations contractuelles sont présentées en langue française.</p>
        </section>

        <section class="term-section">
            <h2>Article 4 : Validation de votre commande</h2>
            <p>Toute commande figurant sur le site Internet MineStore suppose l'adhésion aux présentes Conditions Générales. Toute confirmation de commande entraîne votre adhésion pleine et entière aux présentes conditions générales de vente, sans exception ni réserve.</p>
        </section>

        <section class="term-section">
            <h2>Article 5 : Paiement</h2>
            <p>Le fait de valider votre commande implique pour vous l'obligation de payer le prix indiqué. Le règlement de vos achats s'effectue par carte bancaire grâce au système sécurisé.</p>
        </section>

        <section class="term-section">
            <h2>Article 6 : Rétractation</h2>
            <p>Conformément aux dispositions de l'article L.121-21 du Code de la Consommation, vous disposez d'un délai de rétractation de 14 jours à compter de la réception de vos produits pour exercer votre droit de rétraction sans avoir à justifier de motifs ni à payer de pénalité.</p>
        </section>
    </div>
</div>
@endsection
