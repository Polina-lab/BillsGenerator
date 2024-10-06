<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPivotOrdersBill extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pivot_orders_bills', function(Blueprint $table){
            $table->unsignedBigInteger('order_id')->unsigned();
           // $table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete();
            $table->unsignedBigInteger('bill_id')->unsigned();
           // $table->foreign('bill_id')->references('id')->on('bills')->cascadeOnDelete();
            $table->double("price");
            $table->double('amount')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pivot_orders_bills');
    }
}
