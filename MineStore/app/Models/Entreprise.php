<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entreprise extends Model
{
    protected $primaryKey = 'id_entreprise';

    protected $fillable = [
        'nom',
        'description',
        'email_contact',
        'telephone',
        'adresse',
        'user_id',
        'statut',
        'article_id',
        'team_id',
        'produit_id',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'entreprise_id', 'id_entreprise');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function produits()
    {
        return $this->hasMany(Produit::class, 'entreprise_id', 'id_entreprise');
    }
}

