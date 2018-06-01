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
        $user->name = 'Super admin';
        $user->email = 'super@address.com.ng';
        $user->password = bcrypt('secret');
        $user->username = 'supera';
        $user->phone = '+2348000000000';
        $user->save();

        $user->roles()->save(Role::where('name', 'super')->first());
    }
}
