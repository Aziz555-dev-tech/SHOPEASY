<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribution extends Model
{
    use HasFactory;

    protected $fillable = [
        'bien_id', 'client_id', 'date_attribution', 'prix', 'statut_paiement',
    ];
    

    protected $casts = [
        'date_attribution' => 'date',
    ];

    protected $appends = ['status'];

    
    // Attribution → Bien
    public function bien()
    {
        return $this->belongsTo(Bien::class);
    }    

    // Attribution → Client
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    // Attribution → Paiements
    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }

    

}


