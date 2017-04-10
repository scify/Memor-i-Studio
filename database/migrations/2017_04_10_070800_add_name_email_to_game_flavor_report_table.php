<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNameEmailToGameFlavorReportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('game_flavor_report', function ($table) {
            $table->integer('user_id')->nullable()->unsigned()->change();
            $table->string('user_name')->nullable();
            $table->string('user_email')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('game_flavor_report', function (Blueprint $table) {
            $table->integer('user_id')->nullable(false)->unsigned()->change();
            $table->dropColumn(['user_name', 'user_email']);
        });
    }
}
