<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();

        // Create admin
        $adminRole = Role::lookupRole(Role::ADMIN);

        $admin = new User();
        $admin->name = 'Admin';
        $admin->username = 'admin';
        $admin->email = 'admin@admin.com';
        $admin->mobile_number = '';
        $admin->identity_passport = '';
        $admin->pre_registration = false;
        $admin->activated = true;
        $admin->role_id = $adminRole->id;
        $admin->password = '123456';
        $admin->save();
    }
}
