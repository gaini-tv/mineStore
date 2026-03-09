<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $table = 'articles';
    protected $primaryKey = 'id_article';

    protected $fillable = [
        'nom',
        'description',
    ];

    public function produits()
    {
        return $this->belongsToMany(Produit::class, 'asso_produit_article', 'article_id', 'produit_id');
    }

    // Helper pour récupérer le premier produit lié (cas d'usage blog)
    public function getProduitAttribute()
    {
        return $this->produits->first();
    }

    // Helper pour récupérer les commentaires du produit lié
    public function getCommentairesAttribute()
    {
        return $this->produit ? $this->produit->commentaires : collect();
    }
}
