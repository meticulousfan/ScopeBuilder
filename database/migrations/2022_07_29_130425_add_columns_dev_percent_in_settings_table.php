<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsDevPercentInSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->integer('minimumDaysBeforeRPayout')->default(0)->nullable();
            $table->unsignedDecimal('devPercentagePerCall',8,2)->default(0)->nullable();
            $table->unsignedDecimal('devPercentagePerProject',8,2)->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('minimumDaysBeforeRPayout');
            $table->dropColumn('devPercentagePerCall');
            $table->dropColumn('devPercentagePerProject');
        });
    }
}
