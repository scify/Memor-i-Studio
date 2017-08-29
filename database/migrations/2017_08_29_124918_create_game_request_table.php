<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGameRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_request', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('status_id')->unsigned();
            $table->foreign('status_id')->references('id')->on('game_request_status_lookup');
            $table->integer('player_initiator_id')->unsigned();
            $table->foreign('player_initiator_id')->references('id')->on('player');
            $table->integer('player_opponent_id')->unsigned();
            $table->foreign('player_opponent_id')->references('id')->on('player');
            $table->integer('game_flavor_id')->unsigned();
            $table->foreign('game_flavor_id')->references('id')->on('game_flavor');
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
        Schema::dropIfExists('game_request');
    }
}
