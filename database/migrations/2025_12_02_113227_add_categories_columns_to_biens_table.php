<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('biens', function (Blueprint $table) {
            
            if (!Schema::hasColumn('biens', 'categorie_id')) {
                $table->unsignedBigInteger('categorie_id')->nullable()->after('id');
            }

            if (!Schema::hasColumn('biens', 'sous_categorie_id')) {
                $table->unsignedBigInteger('sous_categorie_id')->nullable()->after('categorie_id');
            }

            if (!Schema::hasColumn('biens', 'sub_type_id')) {
                $table->unsignedBigInteger('sub_type_id')->nullable()->after('sous_categorie_id');
            }
        });
    }

    public function down()
    {
        Schema::table('biens', function (Blueprint $table) {
            $table->dropColumn(['categorie_id', 'sous_categorie_id', 'sub_type_id']);
        });
    }
};
