<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSetDescriptionResourceIdAndDescriptionProbabilityToEquivalenceSetTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('equivalence_set', function ($table) {
            $table->integer('description_sound_id')->unsigned()->nullable();
            $table->integer('description_sound_probability')->unsigned()->nullable();
        });

        Schema::table('equivalence_set', function ($table) {
            $table->foreign('description_sound_id')->references('id')->on('resource');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('equivalence_set', function (Blueprint $table) {
            $table->dropForeign(['description_sound_id']);
            $table->dropColumn('description_sound_id');
            $table->dropColumn('description_sound_probability');
        });
    }
}
