<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDataPackDirNameToGameVersions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('game_version', function (Blueprint $table) {
            $table->string('data_pack_dir_name')->default('scify_pack')->after('version_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('game_version', function (Blueprint $table) {
            $table->dropColumn('data_pack_dir_name');
        });
    }
}
