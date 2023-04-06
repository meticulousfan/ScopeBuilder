<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBbbDefaultCreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bbb_default_create_settings', function (Blueprint $table) {
            $table->id();
            $table->efficientUuid('uuid')->nullable(true)->index();
            $table->boolean('record')->default(true);
            $table->boolean('autoStartRecording')->default(true);
            $table->boolean('allowStartStopRecording')->default(false);
            $table->integer('maxParticipants')->default(2);
            $table->string('bannerText')->nullable(true);
            $table->string('bannerColor')->nullable(true);
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
        Schema::dropIfExists('bbb_default_create_settings');
    }
}
