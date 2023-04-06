<?php

namespace Database\Seeders;

use App\Models\ProjectType;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RoleSeeder::class,
            CountryList::class,
			BBBDefaultCreateSettingsSeeder::class,
 			BBBDefaultJoinSettingsSeeder::class,
 			PayoutMethodsSeeder::class,
 			SettingsTableSeeder::class
        ]);
    }
}