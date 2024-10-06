<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("phone")->nullable();
            $table->string("address")->nullable();
            $table->string("reg_num")->nullable();
            $table->text("comment")->nullable();
            $table->string("email")->nullable();
            $table->boolean("lepping")->default(0);
            $table->boolean("status")->default(1);
            $table->text("extra")->nullable();
            $table->unsignedBigInteger("client_id")->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->timestamps();
        });


        Schema::table('companies', function(Blueprint $table){
            $table->foreign('client_id')->references('id')->on('clients');
        });

/*
        Schema::create('pivot_companies_location', function (Blueprint $table) {
            $table->integer("company_id");
            $table->integer("location_id");
            $table->string("type");
        });*/

        /*
        Schema::create('pivot_clients_location', function (Blueprint $table) {
            $table->integer("client_id");
            $table->integer("location_id");
            $table->string("type");
        });
        */

        }




    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        /*
        Schema::table('companies', function(Blueprint $table) {
            $table->dropForeign(['client_id']);
        });
        */
        Schema::dropIfExists('companies');

    }
}
