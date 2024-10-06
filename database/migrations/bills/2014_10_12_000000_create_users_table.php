<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string("firstname")->nullable();
            $table->string("lastname")->nullable();
            $table->string("address")->nullable();
            $table->string('email')->unique();
            $table->string("phone")->nullable();
            $table->string('avatar' )->nullable();
            $table->tinyInteger("sex")->nullable();
            $table->tinyInteger("age")->nullable();
            $table->date("birthDay")->nullable();
            $table->timestamp("has_contract")->nullable();
            $table->integer("country_id")->nullable();
            $table->integer('langs_id')->unsigned()->default(1);
            $table->integer('roles_id')->unsigned()->default(1);
            $table->timestamp('email_verified_at')->nullable();
            $table->boolean('is_enabled')->default(true);
            $table->boolean('has_multi')->default(false);
            $table->string('password');
            $table->tinyInteger('status')->default(0);
            $table->rememberToken();
            $table->timestamps();
        });



    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        /*
        Schema::table('temp_passwords', function(Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
        */

        Schema::drop('users');

    }
}
