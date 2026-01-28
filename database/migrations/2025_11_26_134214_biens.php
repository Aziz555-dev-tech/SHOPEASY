<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('biens', function (Blueprint $table) {
            $table->id();

            $table->string('titre');
            $table->text('description')->nullable();
            $table->string('adresse')->nullable(); // pas obligatoire pour tous les biens
            $table->decimal('prix', 15, 2)->nullable(); // certains produits peuvent ne pas avoir un prix fixe
            $table->string('image')->nullable();

            /**
             * Type : vente / location
             * (pour boutique ou catalogue)
             */
            $table->enum('type', ['vente', 'location'])->default('vente');

            /**
             * Catégorie principale
             * (équivalent mega-menu : Vêtements, Téléphones, Cuisine, Sport, etc.)
             */
            $table->string('categorie');

            /**
             * Sous-catégorie
             * (Vêtements → Hommes / Femmes / Enfants)
             * (Téléphones → Android / iPhone / Accessoires)
             * (Cuisine → Ustensiles / Electroménager / Vaisselle)
             */
            $table->string('etat')->nullable();

            /**
             * Statut du bien
             */
            $table->enum('statut', ['disponible', 'attribue'])->default('disponible');

            /**
             * Propriétaire du produit / bien
             */
            $table->foreignId('proprietaire_id')
                ->constrained('users')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('biens');
    }
};
