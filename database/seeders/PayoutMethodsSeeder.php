<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PayoutMethods;

class PayoutMethodsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Clear the payout methods table
        PayoutMethods::truncate();

        // Generate 2 default payout method records
        PayoutMethods::create([
            'name' => 'Paypal',
            'description' => 'Get paid via PayPal',
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        PayoutMethods::create([
            'name' => 'Payoneer',
            'description' => 'Get paid via Payoneer',
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
