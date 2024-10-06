<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCollumnPayerForTeam extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('teams' , function (Blueprint $table) {
            $table->boolean('payer')->default(0)->after('timezone');//can be 0 - for company , 1 for user
            $table->tinyInteger('locale' )->default(1)->after('payer');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropColumns('teams' , ['payer' , 'locale']);
    }
}
