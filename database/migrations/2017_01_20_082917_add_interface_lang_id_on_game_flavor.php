<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInterfaceLangIdOnGameFlavor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('game_flavor', function ($table) {
            $table->integer('interface_lang_id')->unsigned()->default(1);
        });

        Schema::table('game_flavor', function ($table) {
            $table->foreign('interface_lang_id')->references('id')->on('language');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('game_flavor', function(Blueprint $table){
            $table->dropForeign('game_flavor_interface_lang_id_foreign');
            $table->dropColumn('interface_lang_id');
        });
    }
}
