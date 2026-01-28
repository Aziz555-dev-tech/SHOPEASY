<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre', 'slug', 'image', 'resume', 'contenu',
        'user_id', 'categorie_articles_id', 'publie'
    ];

    public function categorie()
    {
        return $this->belongsTo(CategorieArticle::class);
    }

    public function auteur()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
