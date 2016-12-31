<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCoverImgToGameVersionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('game_version', function ($table) {
          $table->integer('cover_img_id')->unsigned();
      });

      Schema::table('game_version', function ($table) {
          $table->foreign('cover_img_id')->references('id')->on('image');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('game_version', function(Blueprint $table){
          $table->dropForeign('game_version_cover_img_id_foreign');
          $table->dropColumn('cover_img_id');
      });
    }
}
