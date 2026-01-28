<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SousCategory extends Model {
    protected $fillable = ['category_id','name','slug'];

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function subTypes() {
        return $this->hasMany(SubType::class);
    }
}
