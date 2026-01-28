<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('biens', function (Blueprint $table) {
            if (Schema::hasColumn('biens', 'categorie')) {
                $table->dropColumn('categorie');
            }
        });
    }

    public function down()
    {
        Schema::table('biens', function (Blueprint $table) {
            $table->string('categorie')->nullable();
        });
    }
};

