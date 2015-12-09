<?php

use Illuminate\Database\Seeder;
use Katniss\Models\Permission;
use Katniss\Models\Role;
use Katniss\Models\User;

class DefaultSeeder extends Seeder
{
    public function run()
    {
        $admin_access_permission = Permission::create([
            'name' => 'access-admin',
            'display_name' => 'Access admin',
            'description' => 'Access admin pages'
        ]);

        $owner_role = Role::create(array(
            'name' => 'owner',
            'display_name' => 'Owner',
            'description' => 'Owner of the system',
            'status' => Role::STATUS_HIDDEN,
        ));
        $owner_role->attachPermission($admin_access_permission);

        $admin_role = Role::create(array(
            'name' => 'admin',
            'display_name' => 'Administrator',
            'description' => 'Manage operation of the system and important modules'
        ));
        $admin_role->attachPermission($admin_access_permission);

        $tester_role = Role::create(array(
            'name' => 'tester',
            'display_name' => 'Tester',
            'description' => 'Tester of the system',
            'status' => Role::STATUS_HIDDEN,
        ));
        $tester_role->attachPermission($admin_access_permission);

        $user_role = Role::create(array(
            'name' => 'user',
            'display_name' => 'User',
            'description' => 'Normal user'
        ));

        // TODO: Add 1 administrator
        $owner = User::create(array(
            'display_name' => 'Owner',
            'name' => 'owner',
            'email' => 'owner@katniss.linhntaim.com',
            'password' => bcrypt(')^KM$bB-W7:Z@8eG'),
            'url_avatar' => appDefaultUserProfilePicture(),
            'url_avatar_thumb' => appDefaultUserProfilePicture(),
            'activation_code' => str_random(32),
            'active' => true
        ));
        $owner->attachRole($owner_role);

        $admin = User::create(array(
            'display_name' => 'Administrator',
            'name' => 'admin',
            'email' => 'admin@katniss.linhntaim.com',
            'password' => bcrypt('123456'),
            'url_avatar' => appDefaultUserProfilePicture(),
            'url_avatar_thumb' => appDefaultUserProfilePicture(),
            'activation_code' => str_random(32),
            'active' => true
        ));
        $admin->attachRoles([$admin_role, $owner_role]);

        $tester = User::create(array(
            'display_name' => 'Tester',
            'name' => 'tester',
            'email' => 'tester@katniss.linhntaim.com',
            'password' => bcrypt('123456'),
            'url_avatar' => appDefaultUserProfilePicture(),
            'url_avatar_thumb' => appDefaultUserProfilePicture(),
            'activation_code' => str_random(32),
            'active' => true
        ));
        $tester->attachRole($tester_role);
    }
}
