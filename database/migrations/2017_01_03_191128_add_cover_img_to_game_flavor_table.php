<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCoverImgToGameFlavorTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('game_flavor', function ($table) {
            $table->integer('cover_img_id')->nullable()->unsigned();
        });

        Schema::table('game_flavor', function ($table) {
            $table->foreign('cover_img_id')->references('id')->on('resource');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('game_flavor', function (Blueprint $table) {
            $table->dropForeign('game_flavor_cover_img_id_foreign');
            $table->dropColumn('cover_img_id');
        });
    }
}
