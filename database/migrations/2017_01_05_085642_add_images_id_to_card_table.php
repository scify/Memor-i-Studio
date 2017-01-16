<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddImagesIdToCardTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('card', function ($table) {
            $table->integer('image_id')->unsigned();
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
    public function down()
    {
        Schema::table('card', function(Blueprint $table){
            $table->dropForeign('card_image_id_foreign');
            $table->dropColumn('image_id');
            $table->dropForeign('card_negative_image_id_foreign');
            $table->dropColumn('negative_image_id');
        });
    }
}
