<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('messages', function (Blueprint $table) {

            // Supprimer l'ancienne clé étrangère
            $table->dropForeign(['conversation_id']);

            // Recréer la bonne clé étrangère
            $table->foreign('conversation_id')
                  ->references('id')
                  ->on('conversations')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {

            // Supprimer la bonne clé
            $table->dropForeign(['conversation_id']);

            // Revenir à l'ancienne (mauvaise) si rollback
            $table->foreign('conversation_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }
};
