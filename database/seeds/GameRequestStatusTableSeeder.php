<?php

use Illuminate\Database\Seeder;

class GameRequestStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('game_request_status_lookup')->delete();
        DB::table('game_request_status_lookup')->insert(array(
            array('id'=> 1, 'status' => 'request_sent'),
            array('id'=> 2, 'status' => 'accepted_by_opponent'),
            array('id'=> 3, 'status' => 'rejected_by_opponent'),
            array('id'=> 4, 'status' => 'in_progress'),
            array('id'=> 5, 'status' => 'completed')
        ));
    }
}
