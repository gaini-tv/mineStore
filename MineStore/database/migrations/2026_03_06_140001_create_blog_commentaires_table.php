<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blog_commentaires', function (Blueprint $table) {
            $table->id('id_blog_commentaire');
            $table->foreignId('blog_article_id')->constrained('blog_articles', 'id_blog_article')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('contenu');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blog_commentaires');
    }
};
