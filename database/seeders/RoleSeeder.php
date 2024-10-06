<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;


class RoleSeeder extends Seeder
{

    protected function array_add(array  $permissions , int $role){
        foreach($permissions as $p) {
            DB::table("pivot_role_permissions")->insert([
                'permissions_id' => $p,
                'roles_id' => $role
            ]);
        }
        return ;
    }


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            'name' => 'superAdmin',
            'sys_name' => 'superAdmin',
            'is_enabled' => 1
        ]);


        DB::table('roles')->insert([
            'name' => 'Admin',
            'sys_name' => 'admin',
            'is_enabled' => 1
        ]);

        DB::table('roles')->insert([
            'name' => 'User',
            'sys_name' => 'User',
            'is_enabled' => 1
        ]);


        $permissions = range(1 , 34);

        /*Permissions for Super admin*/
        $this->array_add($permissions , 1);

        /*Permissions for admins*/
        $admin_denied =[
            7, /*view-translate*/
            8  /*show-admin-settings*/
        ];
        $admin_permissions = array_diff($permissions , $admin_denied);
        $this->array_add($admin_permissions , 2);
        /*Permissions for users*/
        $user_denied =[
            3, /*edit-user*/
            4, /*change-user-role*/
            5, /*delete-user*/
            6, /*view-permissions*/
            13,/*delete-bill*/
            16, /*create-role*/
            17, /*edit-role*/
            18, /*delete-role*/
            19, /*edit-permission*/
        ];
        $user_permissions = array_diff($admin_permissions , $user_denied);
        $this->array_add($user_permissions , 3);

    }
}
