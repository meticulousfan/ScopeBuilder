<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create roles and assign existing permissions if the role does not exist
		if (!Role::where('name', 'admin')->exists()) {
			$role1 = Role::create(['name' => 'admin'])->first();
		}
		if (!Role::where('name', 'freelancer')->exists()) {
			$role2 = Role::create(['name' => 'freelancer']);
		}
		if (!Role::where('name', 'client')->exists()) {
			$role3 = Role::create(['name' => 'client']);
		}
    
        $user = User::create([
            'email'     => 'admin@admin.com',
            'password'  => bcrypt('password'),
            'name'      => 'Admin',
            'is_email_verified' => 1
        ]);

        $user->assignRole($role1);
    }
}