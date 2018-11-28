<?php

use App\Role;
use App\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user->firstname = 'Super';
        $user->lastname = 'Admin';
        $user->email = 'super@test.com';
        $user->password = bcrypt('secret');
        $user->username = 'super';
        $user->phone = '+2348000000000';
        $user->verified = 1;
        $user->enabled = 1;
        $user->save();

        $user->roles()->save(Role::where('name', 'super-admin')->first());
    }
}
