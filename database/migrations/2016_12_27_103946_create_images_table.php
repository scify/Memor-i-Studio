<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImagesTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('image', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('category_id')->unsigned()->default(1);
            $table->string('file_path', 500);

            $table->foreign('category_id')->references('id')->on('image_category');
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
        Schema::dropIfExists('image');
    }
}
