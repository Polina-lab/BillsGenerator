<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TeamTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teams', function (Blueprint $table) {
             $table->id();
             $table->string("name", 60)->nullable();
             $table->string("database", 60)->unique();
             $table->string("key", 60)->unique();
             $table->integer("payment_plan_id");
             $table->date("active_until" );
             $table->double('current_balance')->default(0.0);
             $table->boolean("has_created")->default(false);
             $table->timestamp('created_at');
        });

        Schema::create('payment_plan' , function(Blueprint $table){
            $table->id();
            $table->string("string")->unique();
            $table->double("price", 8, 2);
            $table->string("currency" , 4)->default("EUR");
            $table->boolean('visible')->default(false);
            $table->text("extra")->nullable();
        });

        Schema::create('pivot_team_users' , function(Blueprint $table){
            //$table->foreignIdFor(\App\Models\Users\Team::class)->references("id")->on('teams');
           // $table->foreignIdFor(\App\Models\Users\Team::class)->references('id')->on('teams')->onDelete('cascade');;
           // $table->foreignIdFor(\App\Models\Users\User::class)->references('id')->on('users')->onDelete('cascade');;
            $table->integer('team_id');
            $table->integer('user_id');

        });

        /*
        Schema::table('teams' , function(Blueprint $table){
            $table->foreign('id')->references("payment_plan_id")->on('payment_plan');
        });
        */

        /*
                Schema::table('clients', function (Blueprint  $table){
                    $table->foreign('team_id')->references('id')->on('teams');
                });

                Schema::table('companies', function(Blueprint $table){
                    $table->foreign('team_id')->references('id')->on('teams');
                });

                Schema::table('langs', function(Blueprint $table){
                    $table->foreign('team_id')->references('id')->on('teams');
                });

                Schema::table('firms', function(Blueprint $table){
                    $table->foreign('team_id')->references('id')->on('teams');
                });

                Schema::table('roles' , function(Blueprint $table){
                    $table->foreign('team_id')->references("id")->on('teams');
                });
        */
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('pivot_team_users' , function(Blueprint $table){
            $table->dropForeign('team_id');
            $table->dropForeign('user_id');
        });

        Schema::table('teams', function(Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        /*
        Schema::table('clients', function(Blueprint $table) {
            $table->dropForeign(['team_id']);
        });

        Schema::table('companies', function(Blueprint $table) {
            $table->dropForeign(['team_id']);
        });
        */

        Schema::table('langs', function(Blueprint $table) {
            $table->dropForeign(['team_id']);
        });

        /*
        Schema::table('firms', function(Blueprint $table) {
            $table->dropForeign(['team_id']);
        });
        */
        Schema::table('roles', function(Blueprint $table) {
            $table->dropForeign(['team_id']);
        });

        Schema::dropIfExists('teams');
        Schema::dropIfExists('pivot_team_users');
    }
}
