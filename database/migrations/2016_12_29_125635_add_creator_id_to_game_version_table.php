<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCreatorIdToGameVersionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('game_version', function ($table) {
            $table->integer('creator_id')->unsigned();
        });

        Schema::table('game_version', function ($table) {
            $table->foreign('creator_id')->references('id')->on('users');
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
            $table->dropForeign('game_version_creator_id_foreign');
            $table->dropColumn('creator_id');
        });
    }
}
