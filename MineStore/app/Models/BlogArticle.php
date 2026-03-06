<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogArticle extends Model
{
    protected $table = 'blog_articles';

    protected $primaryKey = 'id_blog_article';

    protected $fillable = [
        'titre',
        'contenu',
        'produit_id',
        'user_id',
    ];

    public function produit()
    {
        return $this->belongsTo(Produit::class, 'produit_id', 'id_produit');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function commentaires()
    {
        return $this->hasMany(BlogCommentaire::class, 'blog_article_id', 'id_blog_article');
    }

    public function getRouteKeyName(): string
    {
        return 'id_blog_article';
    }
}
