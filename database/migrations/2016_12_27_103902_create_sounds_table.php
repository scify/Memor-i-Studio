<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSoundsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('sound', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('category_id')->unsigned()->default(1);

            $table->string('file_path', 500);
            $table->integer('order_id');

            $table->foreign('category_id')->references('id')->on('sound_category');
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
        Schema::dropIfExists('sound');
    }
}
