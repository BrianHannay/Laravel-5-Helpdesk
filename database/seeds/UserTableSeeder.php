<?php

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
        $admin = new App\User();
        $admin->email = "brian.hannay@itas.ca";
        $admin->first_name = "Administrator";
        $admin->last_name = "User";
        $admin->password = Hash::make("secret");
        $admin->save();
        $admin->roles()->attach(App\Role::getAdmin());
    }
}
