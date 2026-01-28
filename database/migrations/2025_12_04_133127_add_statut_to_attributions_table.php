<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Ajouter la colonne seulement si elle n'existe pas
        if (!Schema::hasColumn('attributions', 'statut')) {
            Schema::table('attributions', function (Blueprint $table) {
                $table->enum('statut', ['en_attente', 'attribue', 'approuve'])
                      ->default('en_attente')
                      ->after('prix');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('attributions', 'statut')) {
            Schema::table('attributions', function (Blueprint $table) {
                $table->dropColumn('statut');
            });
        }
    }
};
