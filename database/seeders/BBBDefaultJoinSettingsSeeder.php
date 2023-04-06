<?php

namespace Database\Seeders;

use App\Models\BbbDefaultJoinSetting;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BBBDefaultJoinSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(DB::table('bbb_default_join_settings')->count() == 0) {
            $bbb_default_join_setting = new BbbDefaultJoinSetting();
            $bbb_default_join_setting->userdata_bbb_record_video = 0;
            $bbb_default_join_setting->userdata_bbb_auto_join_audio = 1;
            $bbb_default_join_setting->userdata_bbb_auto_share_webcam = 1;
            $bbb_default_join_setting->userdata_bbb_skip_check_audio = 1;
            $bbb_default_join_setting->userdata_bbb_listen_only_mode = 0;
            $bbb_default_join_setting->userdata_bbb_custom_style_url = '';
            $bbb_default_join_setting->userdata_bbb_hide_nav_bar = 1;
            $bbb_default_join_setting->userdata_bbb_hide_presentation = 1;
            $bbb_default_join_setting->save();
        }
    }
}