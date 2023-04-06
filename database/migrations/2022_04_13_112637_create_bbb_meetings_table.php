<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBbbMeetingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bbb_meetings', function (Blueprint $table) {
            $table->id();
            $table->efficientUuid('uuid')->nullable(true)->index();
            $table->unsignedBigInteger('project_id')->nullable(true);
            $table->char('bbb_meeting_id', 16)->charset('binary');
            $table->string('bbb_internal_meeting_id')->nullable(true);
            $table->string('name');
            $table->integer('duration');
            $table->string('attendee_pass')->nullable(true);
            $table->string('moderator_pass')->nullable(true);
            $table->boolean('pending')->default(true);
            $table->dateTime('start_time')->nullable(true);
            $table->dateTime('end_time')->nullable(true);
            $table->json('create_params');
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
        Schema::dropIfExists('bbb_meetings');
    }
}
