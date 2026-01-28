<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('biens', function (Blueprint $table) {
    
            // Renommer etat → sous_categorie si pas encore fait
            if (Schema::hasColumn('biens', 'etat')) {
                $table->renameColumn('etat', 'sous_categorie');
            }
    
            // Ajouter sub_type si absent
            if (!Schema::hasColumn('biens', 'sub_type')) {
                $table->string('sub_type')->nullable();
            }
        });
    }
    
    public function down()
    {
        Schema::table('biens', function (Blueprint $table) {
            if (Schema::hasColumn('biens', 'sous_categorie')) {
                $table->renameColumn('sous_categorie', 'etat');
            }
    
            if (Schema::hasColumn('biens', 'sub_type')) {
                $table->dropColumn('sub_type');
            }
        });
    }
    
};
