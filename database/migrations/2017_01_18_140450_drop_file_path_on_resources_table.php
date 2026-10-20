<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropFilePathOnResourcesTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('resource', function (Blueprint $table) {
            $table->dropColumn('file_path');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('resource', function (Blueprint $table) {
            $table->string('file_path', 500);
        });
    }
}
