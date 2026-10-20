<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCardsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('card', function (Blueprint $table) {
            $table->increments('id');
            $table->string('label', 300);
            $table->integer('equivalence_set_id')->unsigned()->default(1);
            $table->foreign('equivalence_set_id')->references('id')->on('equivalence_set');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('card');
    }
}
