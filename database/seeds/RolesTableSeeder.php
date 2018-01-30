<?php

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->delete();

        // Admin
        DB::table('roles')->insert([
            'name' => Role::ADMIN,
            'display_name' => 'Admin'
        ]);

        // User
        DB::table('roles')->insert([
            'name' => Role::USER,
            'display_name' => 'Normal User'
        ]);

        DB::table('roles')->insert([
            'name' => Role::MANAGER,
            'display_name' => 'Event Manager'
        ]);
    }
}
