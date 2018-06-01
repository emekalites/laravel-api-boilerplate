<?php

use App\Role;
use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_user = new Role();
        $role_user->title = 'Super Admin';
        $role_user->name = 'super';
        $role_user->description = 'Super Administrator';
        $role_user->save();

        $role_user = new Role();
        $role_user->title = 'Admin';
        $role_user->name = 'admin';
        $role_user->description = 'Application administrator';
        $role_user->save();

        $role_user = new Role();
        $role_user->title = 'User';
        $role_user->name = 'user';
        $role_user->description = 'User';
        $role_user->save();
    }
}
