<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('bien_id')->nullable()->constrained()->onDelete('set null');

            $table->string('reference')->unique();
            $table->decimal('montant', 15, 2);
            $table->enum('mode_paiement', ['mobile_money', 'carte_credit', 'virement_bancaire']);
            $table->enum('statut', ['en_attente', 'reussi', 'echoue'])->default('en_attente');

            $table->json('details')->nullable(); // infos brutes API (Fedapay, PayPal, etc.)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
