<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    protected $primaryKey = 'id_categorie';
    
    protected $fillable = [
        'nom',
        'description',
    ];

    public function produits()
    {
        return $this->belongsToMany(Produit::class, 'classer', 'categorie_id', 'produit_id', 'id_categorie', 'id_produit');
    }
}
