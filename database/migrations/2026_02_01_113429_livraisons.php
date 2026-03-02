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
        Schema::create('livraisons', function (Blueprint $table) {
            $table->id();
        
            // Relations
            $table->foreignId('paiement_id')->constrained()->cascadeOnDelete();
            $table->foreignId('boutique_id')->constrained()->cascadeOnDelete();
            $table->foreignId('client_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('livreur_id')->nullable()->constrained('users')->nullOnDelete();
        
            // Adresse de livraison
            $table->string('adresse')->nullable();
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
        
            // Statut
            $table->enum('statut', [
                'en_attente',     // créée après paiement
                'assignee',       // livreur trouvé
                'en_livraison',   // livreur en route
                'livree',         // terminée
                'annulee'
            ])->default('en_attente');
        
            // Temps
            $table->timestamp('assignee_at')->nullable();
            $table->timestamp('livree_at')->nullable();
        
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
