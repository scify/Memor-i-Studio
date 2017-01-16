<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGameVersionLanguageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_version_language', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 300);
            $table->integer('lang_id')->unsigned();
            $table->foreign('lang_id')->references('id')->on('language');
            $table->integer('game_version_id')->unsigned();
            $table->foreign('game_version_id')->references('id')->on('game_version');
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
        Schema::dropIfExists('game_version_language');
    }
}
