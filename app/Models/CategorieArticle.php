<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategorieArticle extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'slug'];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
