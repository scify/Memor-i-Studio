<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGameVersionIdToResourceCategoriesTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('resource_category', function ($table) {
            $table->integer('game_version_id')->unsigned();
        });

        Schema::table('resource_category', function ($table) {
            $table->foreign('game_version_id')->references('id')->on('game_version');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('resource_category', function (Blueprint $table) {
            $table->dropForeign(['game_version_id']);
            $table->dropColumn('game_version_id');
        });
    }
}
