<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string("sys_name");
            $table->boolean("is_enabled");
            $table->string("name");
        });

/*
        Schema::table('users', function($table) {
            $table->foreign('roles_id')->references('id')->on('roles');
        });*/

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

/*        Schema::table('roles' , function(Blueprint $table) {
            $table->dropForeign(['team_id']);
        });*/

        Schema::dropIfExists('roles');
    }
}
