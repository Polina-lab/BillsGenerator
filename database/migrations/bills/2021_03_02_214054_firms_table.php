<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FirmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('firms', function (Blueprint $table) {
            $table->id();
            $table->string("name", 60);
            $table->string('company_name' , 60)->nullable();
            $table->tinyInteger('status')->default(true);
            $table->string("reg_num", 25)->nullable();
            $table->string("phone", 20)->nullable();
            $table->string("address" , 60)->nullable();
            $table->string("vat_reg_num", 100)->nullable();//
            $table->integer("representatives")->nullable();
            $table->string("representative_custom", 60)->nullable();
            $table->string("representative_name" , 60)->nullable();
            $table->text("footer")->nullable();
            $table->boolean('requisites_visible')->default(false);
            $table->boolean("is_footer_visible")->default(true);
            $table->string("logo" )->nullable();
            $table->integer('km')->default(0);
            $table->string('email', 60)->nullable();
            $table->tinyInteger('view_in_invoice')->default(0);//for logo
            $table->boolean('main_firm')->default(false);
        });

        Schema::create("bank_details", function(Blueprint $table){
            $table->increments("id");
            $table->string("bank_name" ,150)->nullable();
            $table->string('bank_num' , 60)->nullable();
            $table->string("bank_account", 150)->nullable();
            $table->string("bank_swift" , 150)->nullable();
            $table->string("bank_address" , 150)->nullable();
            $table->bigInteger("firm_id");
        });

        Schema::create("representatives" , function(Blueprint $table){
            $table->increments('id');
            $table->string("name", 80);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

       /* Schema::table('firms', function(Blueprint $table) {
            $table->dropForeign(['team_id']);
        });*/

        Schema::dropIfExists('firms');

    }
}
