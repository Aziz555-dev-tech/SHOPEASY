<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {

        // Corriger la date
        DB::statement("ALTER TABLE attributions MODIFY date_attribution DATE DEFAULT CURRENT_DATE");

        // Mettre à jour l'ENUM proprement
        DB::statement("
            ALTER TABLE attributions 
            MODIFY statut ENUM('en_attente', 'attribue', 'approuve') 
            DEFAULT 'en_attente'
        ");

        // statut_paiement (changement simple OK)
        DB::statement("
            ALTER TABLE attributions 
            MODIFY statut_paiement VARCHAR(255) DEFAULT 'en_cours'
        ");
    }

    public function down()
    {
        // On remet en arrière si besoin
        DB::statement("
            ALTER TABLE attributions 
            MODIFY statut ENUM('en_attente', 'attribue') 
            DEFAULT 'en_attente'
        ");

        DB::statement("
            ALTER TABLE attributions 
            MODIFY date_attribution DATE DEFAULT CURRENT_DATE
        ");

        DB::statement("
            ALTER TABLE attributions 
            MODIFY statut_paiement VARCHAR(255) DEFAULT 'en_cours'
        ");
    }
};
