<?php

use Illuminate\Database\Seeder;
use Katniss\Models\UserPermission;
use Katniss\Models\UserRole;
use Katniss\Models\User;

class DefaultSeeder extends Seeder
{
    public function run()
    {
        $admin_access_permission = UserPermission::create([
            'name' => 'access-admin',
            'display_name' => 'Access admin',
            'description' => 'Access admin pages'
        ]);

        $admin_role = UserRole::create(array(
            'name' => 'admin',
            'display_name' => 'Administrator',
            'description' => 'Manage operation of the system and important modules'
        ));
        $admin_role->attachPermission($admin_access_permission);

        // TODO: Add 1 administrator
        $admin = User::create(array(
            'display_name' => 'Administrator',
            'name' => 'admin',
            'email' => 'admin@antoree.com',
            'password' => bcrypt('123456'),
            'activation_code' => str_random(32),
            'active' => true
        ));
        $admin->attachRole($admin_role);
    }
}
