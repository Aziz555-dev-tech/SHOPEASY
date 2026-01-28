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
        Schema::table('attributions', function (Blueprint $table) {
            $table->renameColumn('loyer_mensuel', 'prix');
        });
    }
    
    public function down()
    {
        Schema::table('attributions', function (Blueprint $table) {
            $table->renameColumn('prix', 'loyer_mensuel');
        });
    }
    
};
