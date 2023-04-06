<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddUuidToTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::table('collaborative_projects', function(Blueprint $table){
            $table->efficientUuid('uuid')->index()->nullable()->first();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
    */
    public function down()
    {
        Schema::table('collaborative_projects', function(Blueprint $table){
            $table->dropColumn(['uuid']);
        });
    }
}
