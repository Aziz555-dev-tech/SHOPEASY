<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {

            // Ajouter cart_json si absent
            if (!Schema::hasColumn('transactions', 'cart_json')) {
                $table->json('cart_json')->nullable()->after('mode_paiement');
            }

            // Supprimer le bien_id si existant
            if (Schema::hasColumn('transactions', 'bien_id')) {
                $table->dropForeign(['bien_id']);
                $table->dropColumn('bien_id');
            }
        });

        // Modifier ENUM par SQL brut pour éviter l'erreur Doctrine DBAL
        DB::statement("
            ALTER TABLE transactions 
            MODIFY COLUMN mode_paiement 
            ENUM('mobile_money', 'carte_credit', 'virement_bancaire', 'paypal', 'fedapay') 
            NOT NULL
        ");

        DB::statement("
            ALTER TABLE transactions 
            MODIFY COLUMN statut 
            ENUM('en_attente', 'reussi', 'echoue', 'annule') 
            NOT NULL DEFAULT 'en_attente'
        ");
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (Schema::hasColumn('transactions', 'cart_json')) {
                $table->dropColumn('cart_json');
            }

            // Optionnel : réintroduire bien_id si besoin
            // $table->foreignId('bien_id')->nullable()->constrained()->onDelete('set null');
        });
    }
};
