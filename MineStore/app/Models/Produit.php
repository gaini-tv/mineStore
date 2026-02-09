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
        'reference',
        'actif',
        'date_creation',
    ];

    /**
     * Les colonnes qui doivent être castées en types natifs.
     */
    protected $casts = [
        'prix' => 'decimal:2',
        'stock' => 'integer',
    ];
}
