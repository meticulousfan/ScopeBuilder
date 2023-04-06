<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateBbbDefaultCreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bbb_default_create_settings', function (Blueprint $table) {
            $table->boolean('endWhenNoModerator')->default(0)->nullable();
            $table->boolean('endWhenNoModeratorDelayInMinutes')->default(0)->nullable();
            $table->integer('moderatorOnlyMessage')->default(0)->nullable();
            $table->boolean('virtualBackgroundsDisabled')->default(0)->nullable();
            $table->boolean('learningDashboardEnabled')->default(0)->nullable();
            $table->json('disabledFeatures')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bbb_default_create_settings', function (Blueprint $table) {
            $table->dropColumn('endWhenNoModerator');
            $table->dropColumn('endWhenNoModeratorDelayInMinutes');
            $table->dropColumn('moderatorOnlyMessage');
            $table->dropColumn('virtualBackgroundsDisabled');
            $table->dropColumn('learningDashboardEnabled');
            $table->dropColumn('disabledFeatures');
        });
    }
}
