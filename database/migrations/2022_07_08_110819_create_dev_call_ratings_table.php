<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDevCallRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dev_call_ratings', function (Blueprint $table) {            
            $table->id();
            $table->efficientUuid('uuid')->nullable(true)->index();
            $table->unsignedBigInteger('dev_id')->nullable(true);
            $table->unsignedBigInteger('client_id')->nullable(true);
            $table->unsignedBigInteger('project_id')->nullable(true);
            $table->integer('stars')->default(0);
            $table->string('message');
            $table->foreignId('bbbRecordId')->constrained('bbb_recordings')->onDelete('cascade');
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
        Schema::dropIfExists('dev_call_ratings');
    }
}
