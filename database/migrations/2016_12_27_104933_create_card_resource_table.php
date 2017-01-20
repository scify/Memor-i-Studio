<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCardResourceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::create('dynamic_resource_file', function (Blueprint $table) {
//            $table->increments('id');
//            $table->integer('resource_id')->unsigned();
//            $table->integer('card_id')->unsigned();
//            $table->string('file_path', 500);
//            $table->foreign('resource_id')->references('id')->on('resource');
//            $table->foreign('card_id')->references('id')->on('card');
//            $table->timestamps();
//            $table->softDeletes();
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dynamic_resource_file');
    }
}
