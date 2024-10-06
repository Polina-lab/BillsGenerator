<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PaymentStore extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_store', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->dateTime('pay_time')->default(Carbon\Carbon::now());
            $table->text('info')->nullable();
            $table->double('price' )->default(0.0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_store');
    }
}
