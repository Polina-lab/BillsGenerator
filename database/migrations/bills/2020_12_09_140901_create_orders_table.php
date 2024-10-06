<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string("name");
           // $table->double("amount", 8, 2)->default(1);
            $table->integer('firms_id')->nullable();
            $table->string('desc');
            $table->string('unit' , 10)->default("Object")->nullable();
            $table->double("unit_price", 12 , 2);
            $table->boolean('active' )->default(false);
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
        Schema::dropIfExists('orders');
    }
}
