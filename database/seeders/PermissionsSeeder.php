<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* - - - Admin section */
        /*1*/
        DB::table('permissions')->insert([
            'sys_name' => "create-user",
            'description' => "Create User"
        ]);

        /*2*/
        DB::table('permissions')->insert([
            'sys_name' => "view-user",
            'description' => "View user"
        ]);

        /*3*/
        DB::table('permissions')->insert([
            'sys_name' => 'edit-user',
            'description' => 'Edit User',
        ]);

        /*4*/
        DB::table("permissions")->insert([
            'sys_name' => "change-user-role",
            'description' => "change user role"
        ]);

        /*5*/
        DB::table("permissions")->insert([
            'sys_name' => "delete-user",
            'description' => "Delete User"
        ]);

        /*6*/
        DB::table("permissions")->insert([
            'sys_name' => "view-permissions",
            'description' => "View permissions"
        ]);

        /*7*/
        DB::table("permissions")->insert([
            'sys_name' => "view-translate",
            'description' => "View Translate"
        ]);

        /*8*/
        DB::table("permissions")->insert([
            'sys_name' => "show-admin-settings",
            'description' => "Show Admin Settings"
        ]);

        /*9*/
        DB::table("permissions")->insert([
            'sys_name' => "edit-bill",
            'description' => "edit-bill"
        ]);

        /*10*/
        DB::table("permissions")->insert([
            'sys_name' => "edit-bill-details",
            'description' => "Edit bill details"
        ]);

        /*11*/
        DB::table("permissions")->insert([
            'sys_name' => "clone-bill",
            'description' => "clone-bill"
        ]);

        /*12*/
        DB::table("permissions")->insert([
            'sys_name' => "mark-bill-paid",
            'description' => "mark-bill-paid"
        ]);

        /*13*/
        DB::table("permissions")->insert([
            'sys_name' => "delete-bill",
            'description' => "Delete Bill"
        ]);

        /*14*/
        DB::table("permissions")->insert([
            'sys_name' => "add-new-firm",
            'description' => "add-new-firm"
        ]);

        /*15*/
        DB::table("permissions")->insert([
            'sys_name' => "edit-firm",
            'description' => "edit-firm"
        ]);

        /*16*/
        DB::table("permissions")->insert([
            'sys_name' => "create-role",
            'description' => "Create new role"
        ]);

        /*17*/
        DB::table("permissions")->insert([
            'sys_name' => "edit-role",
            'description' => "Edit role"
        ]);

        /*18*/
        DB::table("permissions")->insert([
            'sys_name' => "delete-role",
            'description' => "Delete role"
        ]);

        /*19*/
        DB::table("permissions")->insert([
            'sys_name' => "edit-permission",
            'description' => "Edit permission"
        ]);

        /*20*/
        DB::table("permissions")->insert([
            'sys_name' => "view-bill-price",
            'description' => "View price of every bill"
        ]);

        /*21*/
        DB::table("permissions")->insert([
            'sys_name' => "view-bill-details",
            'description' => "View details for selected bill"
        ]);

        /*22*/
        DB::table("permissions")->insert([
            'sys_name' => "send-bill",
            'description' => "Send bill via email"
        ]);

        /*23*/
        DB::table("permissions")->insert([
            'sys_name' => "download-bill",
            'description' => "Download bill as pdf"
        ]);

        /*24*/
        DB::table("permissions")->insert([
            'sys_name' => "delete-category",
            'description' => "delete-category"
        ]);

        /* - - - User permissions */

        /*25*/
        DB::table("permissions")->insert([
            'sys_name' => "create-bill",
            'description' => "Create bill number"
        ]);

        /*26*/
        DB::table("permissions")->insert([
            'sys_name' => "create-full-bill",
            'description' => "create full Bill"
        ]);

        /*27*/
        DB::table("permissions")->insert([
            'sys_name' => "create-client",
            'description' => "create-client"
        ]);

        /*28*/
        DB::table("permissions")->insert([
            'sys_name' => "view-client",
            'description' => "view-client"
        ]);

        /*29*/
        DB::table("permissions")->insert([
            'sys_name' => "edit-client",
            'description' => "edit-client"
        ]);

        /*30*/
        DB::table("permissions")->insert([
            'sys_name' => "delete-client",
            'description' => "delete-client"
        ]);

        /*31*/
        DB::table("permissions")->insert([
            'sys_name' => "view-company",
            'description' => "view-company"
        ]);

        /*32*/
        DB::table("permissions")->insert([
            'sys_name' => "edit-company",
            'description' => "edit-company"
        ]);

        /*33*/
        DB::table("permissions")->insert([
            'sys_name' => "create-category",
            'description' => "create_category"
        ]);

        /*34*/
        DB::table("permissions")->insert([
            'sys_name' => "edit-category",
            'description' => "edit_category"
        ]);
    }
}
