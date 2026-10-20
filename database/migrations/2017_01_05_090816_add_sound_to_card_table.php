<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSoundToCardTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('card', function ($table) {
            $table->integer('sound_id')->unsigned()->nullable();
        });

        Schema::table('card', function ($table) {
            $table->foreign('sound_id')->references('id')->on('resource');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('card', function (Blueprint $table) {
            $table->dropForeign(['sound_id']);
            $table->dropColumn('sound_id');
        });
    }
}
