<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->string("sys_name");
            $table->string("description");
        });

        Schema::create("pivot_role_permissions" , function(Blueprint $table){
            $table->integer('roles_id')->unsigned();
            $table->foreign('roles_id')->references('id')->on('roles');
            $table->integer('permissions_id')->unsigned();
            $table->foreign('permissions_id')->references('id')->on('permissions');
        });

        Schema::create("pivot_additional_permissions" , function(Blueprint $table){
            $table->foreignIdFor(\App\Models\Users\User::class)->references('id')->on('users');
            $table->integer('permissions_id')->unsigned();
            $table->foreign('permissions_id')->references('id')->on('permissions');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {


        Schema::table('pivot_role_permissions' , function(Blueprint $table) {
            $table->dropForeign(['roles_id']);
            $table->dropForeign(['permissions_id']);
        });


        Schema::table('pivot_additional_permissions' , function(Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['permissions_id']);
        });

/*
        Schema::table('roles' , function(Blueprint $table) {
            $table->dropForeign(['team_id']);
        });*/


        Schema::dropIfExists('permissions');
        Schema::dropIfExists("pivot_role_permissions");
        Schema::dropIfExists("pivot_additional_permissions");
    }
}
