<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('entreprises', function (Blueprint $table) {
            if (!Schema::hasColumn('entreprises', 'deletion_token')) {
                $table->string('deletion_token', 100)->nullable()->after('statut');
            }
            if (!Schema::hasColumn('entreprises', 'deletion_token_expires_at')) {
                $table->dateTime('deletion_token_expires_at')->nullable()->after('deletion_token');
            }
        });
    }

    public function down(): void
    {
        Schema::table('entreprises', function (Blueprint $table) {
            if (Schema::hasColumn('entreprises', 'deletion_token_expires_at')) {
                $table->dropColumn('deletion_token_expires_at');
            }
            if (Schema::hasColumn('entreprises', 'deletion_token')) {
                $table->dropColumn('deletion_token');
            }
        });
    }
};

