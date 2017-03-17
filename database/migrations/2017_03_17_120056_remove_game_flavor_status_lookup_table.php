<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveGameFlavorStatusLookupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('game_flavor_status_lookup');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('game_flavor_status_lookup', function (Blueprint $table) {
            $table->increments('id');
            $table->string('status', 1000);
            $table->string('description', 1000);
            $table->timestamps();
        });
    }
}
