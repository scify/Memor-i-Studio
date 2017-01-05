<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEquivalentCardIdToCardTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('card', function ($table) {
            $table->integer('equivalent_card_id')->unsigned()->nullable();
        });

        Schema::table('card', function ($table) {
            $table->foreign('equivalent_card_id')->references('id')->on('card');
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
            $table->dropForeign('card_equivalent_card_id_foreign');
            $table->dropColumn('equivalent_card_id');
        });
    }
}
