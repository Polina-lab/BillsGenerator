<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFirmVatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('firms_vat', function (Blueprint $table) {
            $table->id();
            $table->string("firm_id");
            $table->integer("vat");
            $table->string('vat_reg_num', 60)->nullable();
            $table->boolean("default")->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('firm_vat');
    }
}
