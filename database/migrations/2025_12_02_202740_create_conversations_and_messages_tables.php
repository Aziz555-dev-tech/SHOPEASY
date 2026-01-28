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
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->string('titre')->nullable();
            $table->unsignedBigInteger('participant1_id')->nullable();
            $table->unsignedBigInteger('participant2_id')->nullable();
            $table->unsignedBigInteger('bien_id')->nullable();
            $table->timestamps();

            // Relations : clés étrangers
            $table->foreign('participant1_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('participant2_id')->references('id')->on('users')->onDelete('cascade');

        });

        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('conversation_id');
            $table->unsignedBigInteger('expediteur_id');
            $table->text('contenu');
            $table->boolean('lu')->default('0');
            $table->timestamps();

            // Relations : clés étrangers
            $table->foreign('conversation_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('expediteur_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
        Schema::dropIfExists('conversations');
    }
};
