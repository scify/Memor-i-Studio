<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFlavorResourceFileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_flavor_resource_file', function (Blueprint $table) {
            $table->increments('id');
            $table->string('file_path', 500);
            $table->integer('resource_id')->unsigned();
            $table->integer('game_flavor_id')->unsigned();
            $table->foreign('resource_id')->references('id')->on('resource');
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
        Schema::dropIfExists('game_flavor_resource_file');
    }
}
