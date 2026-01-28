<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubType extends Model {
    protected $fillable = ['sous_category_id','name','slug'];

    public function sousCategory() {
        return $this->belongsTo(SousCategory::class);
    }
}
