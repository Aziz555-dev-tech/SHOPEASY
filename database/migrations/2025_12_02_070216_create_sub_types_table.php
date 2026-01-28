<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('sub_types', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sous_category_id');
            $table->string('name');
            $table->string('slug')->nullable();
            $table->timestamps();

            $table->foreign('sous_category_id')->references('id')->on('sous_categories')->onDelete('cascade');
            $table->unique(['sous_category_id','name']);
        });
    }

    public function down() {
        Schema::dropIfExists('sub_types');
    }
};
