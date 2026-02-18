<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('entreprises', function (Blueprint $table) {
            if (!Schema::hasColumn('entreprises', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('id_entreprise')->constrained('users')->onDelete('set null');
            }
            if (!Schema::hasColumn('entreprises', 'statut')) {
                $table->string('statut')->default('pending');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'entreprise_id')) {
                $table->foreignId('entreprise_id')->nullable()->after('team_id')->constrained('entreprises', 'id_entreprise')->onDelete('set null');
            }
        });

        Schema::table('produits', function (Blueprint $table) {
            if (!Schema::hasColumn('produits', 'entreprise_id')) {
                $table->foreignId('entreprise_id')->nullable()->after('id_produit')->constrained('entreprises', 'id_entreprise')->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('produits', function (Blueprint $table) {
            if (Schema::hasColumn('produits', 'entreprise_id')) {
                $table->dropConstrainedForeignId('entreprise_id');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'entreprise_id')) {
                $table->dropConstrainedForeignId('entreprise_id');
            }
        });

        Schema::table('entreprises', function (Blueprint $table) {
            if (Schema::hasColumn('entreprises', 'user_id')) {
                $table->dropConstrainedForeignId('user_id');
            }
            if (Schema::hasColumn('entreprises', 'statut')) {
                $table->dropColumn('statut');
            }
        });
    }
};

