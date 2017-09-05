<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangesInPlayerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('player', function ($table) {
            $table->integer('game_flavor_playing')->unsigned()->nullable();
            $table->foreign('game_flavor_playing')->references('id')->on('game_flavor');
            $table->dropColumn('app_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('player', function ($table) {
            $table->string('app_id', 200)->comment('The unique app id produced from the application');
            $table->dropForeign('player_game_flavor_playing_foreign');
            $table->dropColumn('game_flavor_playing');
        });
    }
}
