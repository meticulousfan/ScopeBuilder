<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->boolean('maintenance')->default(0)->nullable();
            $table->string('maintenanceReason')->nullable();
            $table->integer('maintenanceDuration')->default(1)->nullable();
            $table->integer('numberPerTable')->default(5)->nullable();
            $table->string('timezone')->nullable();
            $table->string('locale')->nullable();
            $table->unsignedDecimal('minimumPayoutAmount',8,2)->default(0)->nullable();
            $table->unsignedDecimal('maximumPayoutAmount',8,2)->default(0)->nullable();

            $table->boolean('calls')->default(0)->nullable();
            $table->integer('minimumCallDuration')->default(1)->nullable();
            $table->integer('maximumCallDuration')->default(1)->nullable();
            $table->integer('callDurationSeleteStep')->default(1)->nullable();
            $table->boolean('extendDuration')->default(0)->nullable();
            
            $table->dropColumn('minimumDepositAmount');
            $table->dropColumn('maximumDepositAmount');
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
            $table->dropColumn('maintenance');
            $table->dropColumn('maintenanceReason');
            $table->dropColumn('maintenanceDuration');
            $table->dropColumn('numberPerTable');
            $table->dropColumn('timezone');
            $table->dropColumn('locale');
            $table->dropColumn('minimumPayoutAmount');
            $table->dropColumn('maximumPayoutAmount');
            $table->dropColumn('calls');
            $table->dropColumn('minimumCallDuration');
            $table->dropColumn('maximumCallDuration');
            $table->dropColumn('callDurationSeleteStep');
            $table->dropColumn('extendDuration');
            $table->unsignedDecimal('minimumDepositAmount',8,2)->default(0);
            $table->unsignedDecimal('maximumDepositAmount',8,2)->default(0);
        });
    }
}
