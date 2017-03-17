<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveGameFlavorStatusFromGameFlavorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('game_flavor', function(Blueprint $table){
            $table->dropForeign('game_flavor_game_status_id_foreign');
            $table->dropColumn('game_status_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('game_flavor', function ($table) {
            $table->integer('game_status_id')->unsigned()->nullable();
        });

        Schema::table('game_flavor', function ($table) {
            $table->foreign('game_status_id')->references('id')->on('game_flavor_status_lookup');
        });
    }
}
