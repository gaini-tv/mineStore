<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('verification_token', 64)->nullable()->after('remember_token');
        });

        DB::table('users')
            ->whereNull('email_verified_at')
            ->update(['email_verified_at' => now()]);

        DB::table('users')
            ->where('role', 'admin')
            ->whereNull('date_naissance')
            ->update(['date_naissance' => '2001-01-01']);

        DB::table('users')
            ->where('role', '!=', 'admin')
            ->whereNull('date_naissance')
            ->update(['date_naissance' => '2005-01-01']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('verification_token');
        });
    }
};

