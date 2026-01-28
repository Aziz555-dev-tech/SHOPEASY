<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attribution_id')->constrained('attributions')->onDelete('cascade');
            $table->decimal('montant', 15, 2);
            $table->date('date_paiement');
            $table->enum('mode', ['cash', 'mobile_money', 'banque']);
            $table->enum('status_paiement', ['paye', 'impaye', 'reste_a_payer'])->default('impaye');
            $table->timestamps();
        });
    }    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};
