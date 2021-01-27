<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Role;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Check if the UsersTable is empty...
        $users = User::all();
        if(!count($users)){
            $user = new User();
            $user->name = "Administrator";
            $user->email = "admin@mail.com";
            $user->password = bcrypt(123456);
            $user->save();

            //Add a new role to the user. In this case "admin" role...
            $user->assignRole('admin');
        }
    }
}
