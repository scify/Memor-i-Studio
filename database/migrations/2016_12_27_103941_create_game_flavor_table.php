<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGameFlavorTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('game_flavor', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 300);
            $table->string('description', 1000);

            $table->integer('lang_id')->unsigned()->default(1);
            $table->foreign('lang_id')->references('id')->on('language');
            $table->integer('game_version_id')->unsigned();
            $table->foreign('game_version_id')->references('id')->on('game_version');

            $table->boolean('is_default')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('game_flavor');
    }
}
