<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = Role::all();
        //Check if record exists in database...
        if(!count($roles)) {
            $admin = Role::create(['name' => 'admin']);
            $user = Role::create(['name' => 'user']);
        }
    }
}
