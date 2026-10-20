<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAllowCloneInGameFlavorTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('game_flavor', function ($table) {
            $table->boolean('allow_clone')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('game_flavor', function (Blueprint $table) {
            $table->dropColumn('allow_clone');
        });
    }
}
