<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCreatorIdToGameFlavorTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('game_flavor', function ($table) {
            $table->integer('creator_id')->unsigned();
        });

        Schema::table('game_flavor', function ($table) {
            $table->foreign('creator_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('game_flavor', function (Blueprint $table) {
            $table->dropForeign('game_flavor_creator_id_foreign');
            $table->dropColumn('creator_id');
        });
    }
}
