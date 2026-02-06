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
}
