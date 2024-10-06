<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id('id');
            $table->string("company_name")->nullable();
            $table->string("firstname")->nullable();
            $table->string("lastname")->nullable();
            $table->boolean("type")->default(0);
            $table->string("address")->nullable();
            $table->string("id_code")->nullable();
            $table->text("comment")->nullable();
            $table->integer('client')->default(0);
            $table->string("link")->nullable();
            $table->tinyInteger("status")->default(1);
            $table->tinyInteger('lepping')->default(0);
            $table->integer('agent')->default(0);
            $table->tinyInteger("work_status")->default(0);
            $table->string("phone")->nullable();
            $table->string("reg_num")->nullable();
            $table->string("email")->nullable();
            $table->boolean("show_in_bills")->default(0);
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
        Schema::dropIfExists('clients');
        Schema::dropIfExists('pivot_client_bill');
    }
}
