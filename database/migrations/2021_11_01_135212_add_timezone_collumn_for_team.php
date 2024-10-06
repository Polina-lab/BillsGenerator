<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTimezoneCollumnForTeam extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('teams' , function (Blueprint $table) {
            $table->string('timezone')->default('Europe/Tallinn')->after('has_created');
            $table->string('currency')->default('EUR')->after('timezone');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropColumns('teams' ,['timezone' ,'currency']);
    }
}
