<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCardImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('card_image', function (Blueprint $table) {
            $table->increments('id');
            $table->string('path', 300);
            $table->integer('card_id')->unsigned()->default(1);
            $table->integer('image_id')->unsigned()->default(1);
            $table->foreign('card_id')->references('id')->on('card');

            $table->foreign('image_id')->references('id')->on('image');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('card_image');
    }
}
