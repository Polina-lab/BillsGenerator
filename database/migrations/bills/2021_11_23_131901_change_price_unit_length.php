<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangePriceUnitLength extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
 /*       if(Schema::hasColumns('orders' , ['unit_price'])) {
            Schema::table('orders', function (Blueprint $table) {
                $table->double("unit_price", 12, 2)->change();
            });
        }*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
