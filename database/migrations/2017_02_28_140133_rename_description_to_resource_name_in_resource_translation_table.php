<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameDescriptionToResourceNameInResourceTranslationTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('resource_translation', function (Blueprint $table) {
            $table->renameColumn('description', 'resource_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('resource_translation', function (Blueprint $table) {
            $table->renameColumn('resource_name', 'description');
        });
    }
}
