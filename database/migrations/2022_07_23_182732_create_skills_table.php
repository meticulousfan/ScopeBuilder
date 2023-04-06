<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSkillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('skills', function (Blueprint $table) {       
            $table->id();
            $table->efficientUuid('uuid')->nullable(true)->index();
            $table->string('name');
            $table->boolean('status')->default(0);
            $table->integer('suggested_by_id')->nullable(true);// suggested user Id
            $table->dateTime('suggested_on')->nullable(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('skills');
    }
}
