<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddResourceCategoryTypeOnResourceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('resource_category', function ($table) {
            $table->integer('type_id')->unsigned()->nullable();
        });

        Schema::table('resource_category', function ($table) {
            $table->foreign('type_id')->references('id')->on('resource_category_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('resource_category', function(Blueprint $table){
            $table->dropForeign('resource_category_type_id_foreign');
            $table->dropColumn('type_id');
        });
    }
}
