<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateBbbDefaultJoinSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bbb_default_join_settings', function (Blueprint $table) {
            $table->boolean('userdata_bbb_auto_join_audio')->default(0)->nullable();
            $table->renameColumn('userdata__bbb_record_video', 'userdata_bbb_record_video');
            $table->boolean('userdata_bbb_auto_share_webcam')->default(0)->nullable();
            $table->boolean('userdata_bbb_skip_check_audio')->default(0)->nullable();
            $table->boolean('userdata_bbb_listen_only_mode')->default(0)->nullable();
            $table->string('userdata_bbb_custom_style_url')->nullable();
            $table->boolean('userdata_bbb_hide_nav_bar')->default(0)->nullable();
            $table->boolean('userdata_bbb_hide_presentation')->default(0)->nullable();
            $table->string('disabledFeatures')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bbb_default_join_settings', function (Blueprint $table) {
            $table->dropColumn('userdata_bbb_auto_join_audio');
            $table->renameColumn('userdata_bbb_record_video', 'userdata__bbb_record_video');
            $table->dropColumn('userdata_bbb_auto_share_webcam');
            $table->dropColumn('userdata_bbb_skip_check_audio');
            $table->dropColumn('userdata_bbb_listen_only_mode');
            $table->dropColumn('userdata_bbb_custom_style_url');
            $table->dropColumn('userdata_bbb_hide_nav_bar');
            $table->dropColumn('userdata_bbb_hide_presentation');
            $table->dropColumn('disabledFeatures');
        });
    }
}
