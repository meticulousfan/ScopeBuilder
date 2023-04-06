<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questionns', function (Blueprint $table) {
            $table->id();
            $table->efficientUuid('uuid')->nullable(true)->index();
            $table->string('description')->nullable();
            $table->string('hint')->nullable();
            $table->integer('step')->nullable();
            $table->integer('order')->nullable();
            $table->integer('questionnaire_id')->nullable();
            $table->boolean('mandatory')->default(0);
            $table->decimal('price')->default(0)->nullable();
            $table->boolean('status')->default(0);
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
        Schema::dropIfExists('questionns');
    }
}
