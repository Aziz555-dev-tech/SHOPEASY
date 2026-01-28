<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BienMedia extends Model
{
    use HasFactory;

    protected $fillable = ['bien_id', 'type', 'path'];

    public function bien()
    {
        return $this->belongsTo(Bien::class);
    }
}
