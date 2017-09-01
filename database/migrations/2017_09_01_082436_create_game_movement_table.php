<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGameMovementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_movement', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('game_request_id')->unsigned();
            $table->foreign('game_request_id')->references('id')->on('game_request');
            $table->string('movement_json');
            $table->bigInteger('timestamp');
            $table->integer('player_id')->unsigned();
            $table->foreign('player_id')->references('id')->on('player');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('game_movement');
    }
}
