<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 0. Default Auth Tables (Merged from 0001_01_01_000000_create_users_table.php)
        // We create 'users' here directly with all fields.
        
        // 1. Team (Created first because Users depend on it)
        Schema::create('teams', function (Blueprint $table) {
            $table->id('id_team');
            $table->string('fonction')->nullable(); // MLD: fonction
            $table->date('date_arrivee')->nullable();
            $table->timestamps();
        });

        // Users Table (Merged & Updated)
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // Default Laravel ID
            $table->string('name'); // Default Laravel field
            $table->string('email')->unique(); // Default Laravel field
            $table->timestamp('email_verified_at')->nullable(); // Default Laravel field
            $table->string('password'); // Default Laravel field
            $table->rememberToken(); // Default Laravel field
            
            // Custom MineStore Fields
            $table->string('nom')->nullable();
            $table->string('prenom')->nullable();
            $table->dateTime('date_inscription')->nullable();
            $table->string('statut')->nullable();
            $table->string('role')->default('user');
            
            // Foreign Key to Team
            $table->foreignId('team_id')->nullable()->constrained('teams', 'id_team')->onDelete('set null');
            
            $table->timestamps(); // Default Laravel field
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // 2. Categorie
        Schema::create('categories', function (Blueprint $table) {
            $table->id('id_categorie');
            $table->string('nom');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // 3. Article
        Schema::create('articles', function (Blueprint $table) {
            $table->id('id_article');
            $table->string('nom');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 4. Paiement
        Schema::create('paiements', function (Blueprint $table) {
            $table->id('id_paiement');
            $table->dateTime('date_paiement');
            $table->decimal('montant', 10, 2);
            $table->string('mode_paiement');
            $table->string('statut');
            $table->string('transaction_id')->nullable();
            $table->timestamps();
        });

        // 5. Panier
        Schema::create('paniers', function (Blueprint $table) {
            $table->id('id_panier');
            $table->dateTime('date_creation');
            $table->string('statut');
            $table->timestamps();
        });

        // 6. Produit
        Schema::create('produits', function (Blueprint $table) {
            $table->id('id_produit');
            $table->string('nom');
            $table->text('description')->nullable();
            $table->decimal('prix', 10, 2);
            $table->integer('stock');
            $table->string('reference')->unique();
            $table->string('image')->nullable();
            $table->boolean('actif')->default(true);
            $table->dateTime('date_creation');
            $table->timestamps();
        });

        // 8. Commande
        Schema::create('commandes', function (Blueprint $table) {
            $table->id('id_commande');
            $table->dateTime('date_commande');
            $table->string('statut');
            $table->decimal('total', 10, 2);
            $table->text('adresse_livraison');
            $table->text('adresse_facturation');
            
            // Foreign Keys
            $table->foreignId('paiement_id')->nullable()->constrained('paiements', 'id_paiement')->onDelete('set null');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            $table->timestamps();
        });

        // 9. Commentaire
        Schema::create('commentaires', function (Blueprint $table) {
            $table->id('id_commentaire');
            $table->text('contenu');
            $table->dateTime('date_');
            $table->string('statut');
            
            // Foreign Keys
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('produit_id')->nullable()->constrained('produits', 'id_produit')->onDelete('cascade');
            
            $table->timestamps();
        });

        // 10. LigneCommande
        Schema::create('ligne_commandes', function (Blueprint $table) {
            $table->id('id_ligneCommande');
            $table->integer('quantité');
            $table->decimal('prix_TTC', 10, 2);
            $table->decimal('prix_HT', 10, 2);
            
            // Foreign Keys
            $table->foreignId('commande_id')->constrained('commandes', 'id_commande')->onDelete('cascade');
            $table->foreignId('produit_id')->constrained('produits', 'id_produit')->onDelete('cascade');
            
            $table->timestamps();
        });

        // 11. LignePanier
        Schema::create('ligne_paniers', function (Blueprint $table) {
            $table->id('id_ligne_panier');
            $table->integer('quantite');
            $table->decimal('prix_snapshot', 10, 2);
            
            // Foreign Keys
            $table->foreignId('panier_id')->constrained('paniers', 'id_panier')->onDelete('cascade');
            $table->foreignId('produit_id')->constrained('produits', 'id_produit')->onDelete('cascade');
            
            $table->timestamps();
        });

        // 12. Entreprise
        Schema::create('entreprises', function (Blueprint $table) {
            $table->id('id_entreprise');
            $table->string('nom');
            $table->text('description')->nullable();
            $table->string('email_contact');
            $table->string('telephone');
            $table->text('adresse');
            
            // Foreign Keys
            $table->foreignId('article_id')->nullable()->constrained('articles', 'id_article')->onDelete('set null');
            $table->foreignId('team_id')->nullable()->constrained('teams', 'id_team')->onDelete('set null');
            $table->foreignId('produit_id')->nullable()->constrained('produits', 'id_produit')->onDelete('set null');
            
            $table->timestamps();
        });

        // 13. Asso_4 (Produit - Article)
        Schema::create('asso_produit_article', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produit_id')->constrained('produits', 'id_produit')->onDelete('cascade');
            $table->foreignId('article_id')->constrained('articles', 'id_article')->onDelete('cascade');
            $table->timestamps();
        });

        // 14. Classer (Produit - Categorie)
        Schema::create('classer', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produit_id')->constrained('produits', 'id_produit')->onDelete('cascade');
            $table->foreignId('categorie_id')->constrained('categories', 'id_categorie')->onDelete('cascade');
            $table->timestamps();
        });

        // 15. Asso_15 (Utilisateur - Panier)
        Schema::create('asso_utilisateur_panier', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('panier_id')->constrained('paniers', 'id_panier')->onDelete('cascade');
            $table->timestamps();
        });

        // 16. Asso_21 (Commentaire - Article)
        Schema::create('asso_commentaire_article', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commentaire_id')->constrained('commentaires', 'id_commentaire')->onDelete('cascade');
            $table->foreignId('article_id')->constrained('articles', 'id_article')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asso_commentaire_article');
        Schema::dropIfExists('asso_utilisateur_panier');
        Schema::dropIfExists('classer');
        Schema::dropIfExists('asso_produit_article');
        Schema::dropIfExists('entreprises');
        Schema::dropIfExists('ligne_paniers');
        Schema::dropIfExists('ligne_commandes');
        Schema::dropIfExists('commentaires');
        Schema::dropIfExists('commandes');
        
        // Drop auth tables
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');

        Schema::dropIfExists('produits');
        Schema::dropIfExists('paniers');
        Schema::dropIfExists('paiements');
        Schema::dropIfExists('articles');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('teams');
    }
};
