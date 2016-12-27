<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGameVersionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_version', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 300);
            $table->string('description', 1000);
            $table->integer('lang_id')->unsigned()->default(1);
            $table->foreign('lang_id')->references('id')->on('language');
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
        Schema::dropIfExists('game_version');
    }
}
