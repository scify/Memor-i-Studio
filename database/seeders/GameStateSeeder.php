<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GameStateSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::table('game_flavor_status_lookup')->delete();
        DB::table('game_flavor_status_lookup')->insert([
            ['id'=> 1, 'status' =>'Submitted for approval', 'description'=>'When finishing creating a game, the creator submits it for admin approval'],
            ['id'=> 2, 'status' =>'Not approved', 'description'=>'If an admin denies a game from approval'],
        ]);
    }
}
