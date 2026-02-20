<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Confirmation de votre inscription</title>
</head>
<body>
    <p>Bonjour {{ $user->prenom }} {{ $user->nom }},</p>
    <p>Merci de vous être inscrit sur MineStore.</p>
    <p>Pour activer votre compte et pouvoir vous connecter, veuillez confirmer votre adresse email en cliquant sur le lien ci-dessous :</p>
    <p>
        <a href="{{ route('verification.verify', ['token' => $user->verification_token]) }}">
            Confirmer mon inscription
        </a>
    </p>
    <p>Si vous n'êtes pas à l'origine de cette inscription, vous pouvez ignorer cet email.</p>
    <p>À très bientôt sur MineStore !</p>
</body>
</html>
