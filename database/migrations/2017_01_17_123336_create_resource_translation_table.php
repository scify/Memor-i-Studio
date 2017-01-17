<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResourceTranslationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resource_translation', function (Blueprint $table) {
            $table->increments('id');
            $table->string('description', 500);
            $table->integer('lang_id')->unsigned();
            $table->foreign('lang_id')->references('id')->on('language');
            $table->integer('resource_id')->unsigned();
            $table->foreign('resource_id')->references('id')->on('resource');
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
        Schema::dropIfExists('resource_translation');
    }
}
