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
            $table->string('email')->unique();
            //$table->string("phone")->nullable();
            //$table->tinyInteger("sex")->nullable();
            //$table->tinyInteger("age")->nullable();
            //$table->date("birthDay")->nullable();
            //$table->timestamp("has_contract")->nullable();
            //$table->integer("country_id")->nullable();
            //$table->integer("town_id")->nullbale();
            //$table->integer('langs_id')->unsigned()->default(1);
            //$table->integer('roles_id')->unsigned()->default(1);
            $table->timestamp('email_verified_at')->nullable();
            $table->boolean('is_enabled')->default(true);
            $table->boolean('has_multi')->default(false);
            $table->bigInteger("local_id")->nullable();
            $table->boolean('has_buyer')->default(false);
            //$table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create("temp_passwords" , function (Blueprint  $table){
          //$table->bigInteger('user_id');
           $table->foreignIdFor(\App\Models\Users\User::class)->references('id')->on('users')->onDelete('cascade');;
           $table->string("password");
           $table->dateTime("created_at");
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('temp_passwords', function(Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::drop('users');
        Schema::drop('temp_passwords');

    }
}
