<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameIdentifierColumnInGameFlavorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('game_flavor', function (Blueprint $table) {
            $table->renameColumn('pack_identifier', 'game_identifier');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('game_flavor', function (Blueprint $table) {
            $table->renameColumn('game_identifier', 'pack_identifier');
        });
    }
}
