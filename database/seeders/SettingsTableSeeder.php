<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(DB::table('settings')->count() == 0) {
            DB::statement("INSERT INTO `settings` (`id`, `pricePerCallMinute`, `defaultQuestionPrice`, `created_at`, `updated_at`, `uuid`, `maintenance`, `maintenanceReason`, `maintenanceDuration`, `numberPerTable`, `timezone`, `locale`, `minimumPayoutAmount`, `maximumPayoutAmount`, `calls`, `minimumCallDuration`, `maximumCallDuration`, `callDurationSeleteStep`, `extendDuration`, `numberOfDevsAvailable`, `timeBeforeChargingClientInMinutes`, `alertSound`, `devProjectReferralCommission`, `maxNumberSkillsDevCanChoose`, `skillSubmission`, `metaTitle`, `metaDescription`, `metaImage`, `referralMetaTitle`, `referralMetaDesc`, `referralMetaImage`, `minimumDaysBeforeRPayout`, `devPercentagePerCall`, `devPercentagePerProject`) VALUES (NULL, '0.50', '0.50', '2023-01-11 14:48:43', '2023-01-11 14:48:43', NULL, '0', 'System update', '1', '5', 'America/California', 'en', '1.00', '2000.00', '1', '1', '120', '5', '1', '5', '1', 'ring.mp3', '1', '5', '1', 'ScopeBuilder', 'A clearer scope for your projects', '/ui_assets/img/logo.png', '%SKILLS%', 'Freelancer can assist you ', '0', '3', '1.00', '3.00');");
        }
    }
}
