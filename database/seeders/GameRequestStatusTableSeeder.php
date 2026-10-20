<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GameRequestStatusTableSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::table('game_request_status_lookup')->delete();
        DB::table('game_request_status_lookup')->insert([
            ['id'=> 1, 'status' => 'request_sent'],
            ['id'=> 2, 'status' => 'accepted_by_opponent'],
            ['id'=> 3, 'status' => 'rejected_by_opponent'],
            ['id'=> 4, 'status' => 'in_progress'],
            ['id'=> 5, 'status' => 'completed'],
        ]);
    }
}
