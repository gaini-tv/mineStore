<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commentaire extends Model
{
    protected $table = 'commentaires';

    protected $primaryKey = 'id_commentaire';

    protected $fillable = [
        'contenu',
        'date_',
        'statut',
        'user_id',
        'produit_id',
        'note',
    ];

    /**
     * Relation avec le produit
     */
    public function produit()
    {
        return $this->belongsTo(Produit::class, 'produit_id', 'id_produit');
    }

    /**
     * Relation avec l'utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Les colonnes qui doivent être castées en types natifs.
     */
    protected $casts = [
        'date_' => 'datetime',
        'note' => 'integer',
    ];
}
