<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Boutique extends Model
{
    protected $fillable = [
        'nom',
        'slug',
        'description',
        'logo',
        'proprietaire_id',
        'active',
        'email',
        'adresse',
        'latitude',
        'longitude',
    ];

    public function proprietaire()
    {
        return $this->belongsTo(User::class, 'proprietaire_id');
    }

    public function biens()
    {
        return $this->hasMany(Bien::class);
    }
}
