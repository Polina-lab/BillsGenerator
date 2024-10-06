<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSwaggersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("method", 10);
            $table->string('name', 100)->nullable();
            $table->string("url" );
            $table->text("desc")->nullable();
            $table->text("example")->nullable();
            $table->bigInteger("user_id");
            $table->timestamps();
        });

        Schema::create('api_request', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger("api_id");
            $table->string("type")->nullable();
            $table->string('value')->nullable();
            $table->string("name" );
            $table->boolean('has_required')->default(1);
            $table->text("desc")->nullable();
        });

        Schema::create("api_response", function (Blueprint $table){
            $table->bigIncrements('id');
            $table->bigInteger("api_id");
            $table->string("type")->nullable();
            $table->string("name" )->nullable();
            $table->text("desc")->nullable();
            $table->string("example")->nullable();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('api');
        Schema::dropIfExists("api_request");
        Schema::dropIfExists("api_response");
    }
}
