<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    protected $table = 'produits';

    protected $primaryKey = 'id_produit';

    protected $fillable = [
        'nom',
        'description',
        'prix',
        'image',
        'stock',
        'stock_low_threshold',
        'infinite_stock',
        'rupture_marketing',
        'reference',
        'actif',
        'date_creation',
        'pegi',
        'entreprise_id',
    ];

    /**
     * Les colonnes qui doivent être castées en types natifs.
     */
    protected $casts = [
        'prix' => 'decimal:2',
        'stock' => 'integer',
        'stock_low_threshold' => 'integer',
        'infinite_stock' => 'boolean',
        'rupture_marketing' => 'boolean',
    ];

    public function entreprise()
    {
        return $this->belongsTo(Entreprise::class, 'entreprise_id', 'id_entreprise');
    }

    public function categories()
    {
        return $this->belongsToMany(Categorie::class, 'classer', 'produit_id', 'categorie_id', 'id_produit', 'id_categorie');
    }
}
