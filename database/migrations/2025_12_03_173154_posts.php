<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->string('slug')->unique(); // pour l’URL
            $table->string('image')->nullable(); // image de couverture
            $table->text('resume')->nullable(); // petit résumé pour la liste
            $table->longText('contenu');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // auteur (admin)
            $table->foreignId('categorie_articles_id')->nullable()->constrained()->onDelete('set null');
            $table->boolean('publie')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
