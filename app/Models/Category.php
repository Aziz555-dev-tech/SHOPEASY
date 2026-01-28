<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model {
    protected $fillable = ['name','slug'];

    public function sousCategories() {
        return $this->hasMany(SousCategory::class);
    }

    public function bien()
    {
        return $this->hasMany(Bien::class);
    }
}
