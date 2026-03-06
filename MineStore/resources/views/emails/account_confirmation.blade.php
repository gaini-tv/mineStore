@extends('emails.layout')

@section('title', 'Confirmation de votre inscription')

@section('content')
    <p>Bonjour {{ $user->prenom ?? $user->name }},</p>
    <p>Merci de vous être inscrit sur MineStore.</p>
    <p>Pour activer votre compte, cliquez sur le bouton ci-dessous :</p>
    <p>
        <a href="{{ route('verification.verify', ['token' => $user->verification_token]) }}" class="btn">Confirmer mon inscription</a>
    </p>
    <p style="font-size: 0.875rem; color: #706f6c;">Si le bouton ne fonctionne pas : {{ route('verification.verify', ['token' => $user->verification_token]) }}</p>
    <p>Si vous n'êtes pas à l'origine de cette inscription, ignorez cet email.</p>
    <p>À bientôt sur MineStore !</p>
@endsection
