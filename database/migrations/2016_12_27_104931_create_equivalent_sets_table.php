<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEquivalentSetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equivalence_set', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 300);
            $table->integer('flavor_id')->unsigned()->default(1);
            $table->foreign('flavor_id')->references('id')->on('game_flavor');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('equivalence_set');
    }
}
