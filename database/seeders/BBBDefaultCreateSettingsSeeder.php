<?php

namespace Database\Seeders;

use App\Models\BbbDefaultCreateSetting;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BBBDefaultCreateSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(DB::table('bbb_default_create_settings')->count() == 0) {
            $bbb_default_create_settings = new BBBDefaultCreateSetting();
            $bbb_default_create_settings->record = 1;
            $bbb_default_create_settings->autoStartRecording = 1;
            $bbb_default_create_settings->allowStartStopRecording = 1;
            $bbb_default_create_settings->maxParticipants = 1;
            $bbb_default_create_settings->bannerText = "This session is being recorded. Webcams will not be recorded.";
            $bbb_default_create_settings->bannerColor = '#ff1a1a';
            $bbb_default_create_settings->endWhenNoModerator = 1;
            $bbb_default_create_settings->endWhenNoModeratorDelayInMinutes = 1;
            $bbb_default_create_settings->moderatorOnlyMessage = 'Moderator';
            $bbb_default_create_settings->virtualBackgroundsDisabled = 1;
            $bbb_default_create_settings->learningDashboardEnabled = 0;
            $bbb_default_create_settings->disabledFeatures = json_encode('chat,polls');
            $bbb_default_create_settings->save();
        }
    }
}