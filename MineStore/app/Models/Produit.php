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
        'pegi',
        'entreprise_id',
    ];

    /**
     * Les colonnes qui doivent être castées en types natifs.
     */
    protected $casts = [
        'prix' => 'decimal:2',
        'stock' => 'integer',
    ];

    public function entreprise()
    {
        return $this->belongsTo(Entreprise::class, 'entreprise_id', 'id_entreprise');
    }
}
