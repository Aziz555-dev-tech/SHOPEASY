<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bien extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'categorie_id',
        'sous_categorie_id',
        'sub_type_id',
        'description',
        'adresse',
        'prix',
        'stock',
        'type',
        'statut',
        'boutique_id',
        'proprietaire_id',
    ];

    public function proprietaire()
    {
        return $this->belongsTo(User::class, 'proprietaire_id');
    }


    public function attributions()
    {
        return $this->hasMany(Attribution::class);
    }

    /** Médias */
    public function medias()
    { 
        return $this->hasMany(BienMedia::class, 'bien_id'); 
    }

    public function categorie()
    {
        return $this->belongsTo(Category::class, 'categorie_id');
    }

    public function sousCategorie()
    {
        return $this->belongsTo(SousCategory::class);
    }

    public function subType()
    {
        return $this->belongsTo(SubType::class);
    }

    public function boutique()
    {
        return $this->belongsTo(Boutique::class);
    }



}

