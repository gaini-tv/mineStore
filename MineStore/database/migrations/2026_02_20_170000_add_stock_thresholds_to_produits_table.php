<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('produits', function (Blueprint $table) {
            $table->integer('stock_low_threshold')->default(100)->after('stock');
            $table->boolean('infinite_stock')->default(false)->after('stock_low_threshold');
        });
    }

    public function down(): void
    {
        Schema::table('produits', function (Blueprint $table) {
            $table->dropColumn('stock_low_threshold');
            $table->dropColumn('infinite_stock');
        });
    }
};

