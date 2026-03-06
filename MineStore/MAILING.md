# Configuration du mailing MineStore

## Activer l'envoi d'emails

Dans `.env`, changez :
```env
MAIL_MAILER=smtp
```

## Gmail : mot de passe d'application

1. Activez la validation en 2 étapes sur Google
2. Paramètres → Sécurité → Mots de passe des applications
3. Créez un mot de passe pour "Mail"
4. Utilisez-le dans `MAIL_PASSWORD`

## Variables `.env` pour Gmail

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=votre-email@gmail.com
MAIL_PASSWORD=mot-de-passe-application
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=minestore-noreply@gmail.com
MAIL_FROM_NAME="MineStore"
```

## Test en local

Avec `MAIL_MAILER=log`, les emails sont écrits dans `storage/logs/laravel.log`.

## Créer un nouvel email

```bash
php artisan make:mail NomDuMail
```

Utilisez le layout `emails.layout` dans vos vues.
