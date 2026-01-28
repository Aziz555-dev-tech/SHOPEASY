<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('paiements', function (Blueprint $table) {

            // Liens
            $table->foreignId('client_id')->constrained('users')->onDelete('cascade');

            // Référence unique pour paiement externe
            $table->string('reference')->unique();

            // Détails supplémentaires (JSON)
            $table->json('details')->nullable();
        });
    }

    public function down(): void
    {

    }
};

