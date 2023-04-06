<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsBbbMeetingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bbb_meetings', function (Blueprint $table) {
            $table->integer('meetingDurationUsed')->default(0);
            $table->unsignedDecimal('pricePerMinute',8,2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bbb_meetings', function (Blueprint $table) {
            $table->dropColumn('meetingDurationUsed');
            $table->dropColumn('pricePerMinute');
        });
    }
}
