<?php

use Illuminate\Database\Seeder;
use App\Role;
use App\User;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = new Role;
        $role->description = "user";
        $role->save();

        $role = new Role;
        $role->description = "tech";
        $role->save();
        $role->users()->attach(User::defaultUser());

        $role = new Role;
        $role->description = "admin";
        $role->save();
    }
}
