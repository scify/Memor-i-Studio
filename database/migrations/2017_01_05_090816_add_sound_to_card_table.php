<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSoundToCardTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('card', function ($table) {
            $table->integer('sound_id')->unsigned();
        });

        Schema::table('card', function ($table) {
            $table->foreign('sound_id')->references('id')->on('sound');
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
            $table->dropForeign('card_sound_id_foreign');
            $table->dropColumn('sound_id');
        });
    }
}
