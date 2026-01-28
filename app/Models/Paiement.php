<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    use HasFactory;

    protected $fillable = [
        'attribution_id', 'client_id', 'montant', 'reference',
        'mode', 'status_paiement', 'date_paiement', 'details'
    ];

    protected $casts = [
        'date_paiement' => 'datetime',
        'details'       => 'array',
    ];

    public function attribution()
    {
        return $this->belongsTo(Attribution::class);
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }
}

