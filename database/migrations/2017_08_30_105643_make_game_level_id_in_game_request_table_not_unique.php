<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class MakeGameLevelIdInGameRequestTableNotUnique extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('game_request', function ($table) {
            $table->dropUnique('game_request_game_level_id_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('game_request', function ($table) {
            $table->unique('game_level_id');
        });
    }
}
