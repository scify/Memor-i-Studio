<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddImagesIdToCardTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('card', function ($table) {
            $table->integer('image_id')->unsigned()->nullable();
            $table->integer('negative_image_id')->unsigned()->nullable();
        });

        Schema::table('card', function ($table) {
            $table->foreign('image_id')->references('id')->on('resource');
            $table->foreign('negative_image_id')->references('id')->on('resource');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('card', function (Blueprint $table) {
            $table->dropForeign(['image_id']);
            $table->dropColumn('image_id');
            $table->dropForeign(['negative_image_id']);
            $table->dropColumn('negative_image_id');
        });
    }
}
