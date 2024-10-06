<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->integer("number");
            //$table->string("name")->default("");
            //$table->integer("firm_id");
            //$table->foreignIdFor(\App\Models\Users\Team::class)->storedAs('team_id')->references('id')->on('teams');
            //$table->foreignIdFor(\App\Models\Bills\Firms::class)->references('id')->on('firms');
            $table->unsignedBigInteger('firm_id')->nullable();
            $table->integer('bank_id')->nullable();
            $table->unsignedBigInteger('act_user_id')->nullable();
            $table->foreignIdFor(\App\Models\Users\User::class)->references('id')->on('users');
            $table->tinyInteger('status')->default(0);
            $table->date("date")->default(\Carbon\Carbon::now()->format("Y-m-d"));
            $table->unsignedBigInteger('client_id')->nullable();
            $table->unsignedBigInteger('sender_user_id')->nullable();
            $table->date("deadline")->nullable();
            $table->tinyInteger("prepaid")->nullable();
            $table->double("penalty")->nullable();
            $table->tinyInteger("payment_method")->nullable();
            $table->tinyInteger('deal')->nullable();
            $table->string("currency" , 4);
        /**payment_method :
         *  {id: 1, name: 'cash'},
            {id: 2, name: 'card'},
            {id: 3, name: 'transfer'}
         */

            $table->boolean("paid_cash")->nullable();
            //$table->foreignIdFor(\App\Models\Langs::class)->;
            //$table->text("comment")->nullable();
            //$table->tinyInteger('has_KM')->default(false);
            $table->date("was_sent")->nullable();
            $table->date("was_paid")->nullable();
            $table->string("invoice" , 10);
            $table->unsignedInteger('locale')->default(1);
            $table->unsignedBigInteger("company_id")->nullable();
            $table->boolean('show_footer')->default(false);//show footer
            $table->boolean('show_requisites')->default(false);//show requisites on bill
            $table->boolean('type')->default(true); // true = outcoming , false = incoming
            $table->integer('vat_id')->nullable();
            $table->double('price')->nullable();// price
            $table->timestamps();
        });

        Schema::table('bills', function($table) {
            //$table->foreign('firm_id')->references('id')->on('firms');
            $table->foreign('firm_id')->references('id')->on('firms');
            $table->foreign('client_id')->references('id')->on('clients');
            $table->foreign('sender_user_id')->references('id')->on('users');
            $table->foreign('act_user_id')->references('id')->on('users');
            $table->foreign("company_id")->references('id')->on('companies');
            $table->foreign('locale')->references('id')->on('langs');
          //  $table->foreignIdFor(\App\Models\Clients\Clients::class)->references('id')->on('clients');
        });
/*
        Schema::table('orders', function($table){
//            $table->foreign('bill_id')->references('id')->on('bills');
            $table->foreignIdFor(\App\Models\Bills\Bills::class)->references('id')->on('bills');

        });*/

        Schema::create('pivot_client_bill', function (Blueprint $table) {
            $table->foreignIdFor(\App\Models\Clients\Clients::class)->references('id')->on('clients');
            $table->foreignIdFor(\App\Models\Bills\Bills::class)->references('id')->on('bills');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {


        Schema::dropIfExists('bills');

        Schema::table('bills', function($table) {
            //$table->dropForeign('client_id');
            //$table->dropForeign('firms_id');
            //$table->dropForeign('user_id');
            //$table->dropForeign('sender_user_id');
            //$table->dropForeign('act_user_id');
            //$table->dropForeign("company_id");
            //$table->dropForeign('locale');
            //  $table->foreignIdFor(\App\Models\Clients\Clients::class)->references('id')->on('clients');
        });

/*
        Schema::table('orders' , function(Blueprint $table){
            $table->dropForeign('bill_id');
        });*/


    }
}
