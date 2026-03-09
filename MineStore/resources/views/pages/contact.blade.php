@extends('layouts.app')

@section('title', 'Contact - MineStore')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/contact.css') }}">
@endpush

@section('content')
<div class="page-container">
    <div class="page-header">
        <h1>Contactez-nous</h1>
        <p class="subtitle">Une question ? Un problème ? Notre équipe est là pour vous aider.</p>
    </div>

    <div class="contact-grid">
        <div class="contact-info">
            <h2>Nos Coordonnées</h2>
            <div class="info-item">
                <span class="icon">📍</span>
                <div>
                    <h3>Adresse</h3>
                    <p>123 Avenue du Creeper<br>75000 Minecraft City</p>
                </div>
            </div>
            <div class="info-item">
                <span class="icon">📧</span>
                <div>
                    <h3>Email</h3>
                    <p>support@minestore.fr</p>
                </div>
            </div>
            <div class="info-item">
                <span class="icon">📞</span>
                <div>
                    <h3>Téléphone</h3>
                    <p>01 23 45 67 89</p>
                </div>
            </div>
            <div class="info-item">
                <span class="icon">🕒</span>
                <div>
                    <h3>Horaires</h3>
                    <p>Lundi - Vendredi : 9h - 18h<br>Samedi : 10h - 17h</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
