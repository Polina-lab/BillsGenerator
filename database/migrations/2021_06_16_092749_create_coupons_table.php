<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code' , 8)->unique();
            $table->integer('amount_percent')->nullable();
            $table->double('amount_eur')->nullable();
            $table->date('start_date')->default(Carbon\Carbon::now());
            $table->string('for_who')->nullable();
            $table->string("access_days")->default(0);
            $table->date('end_date')->nullable();
            $table->tinyInteger('usages_remaining')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->date('activated_at')->nullable();
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
        Schema::dropIfExists('coupons');
    }
}
