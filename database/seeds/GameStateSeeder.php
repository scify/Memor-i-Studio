<?php

use Illuminate\Database\Seeder;

class GameStateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('game_flavor_status_lookup')->delete();
        DB::table('game_flavor_status_lookup')->insert(array(
            array('id'=> 1, 'status' =>'Submitted for approval', 'description'=>'When finishing creating a game, the creator submits it for admin approval'),
            array('id'=> 2, 'status' =>'Not approved', 'description'=>'If an admin denies a game from approval')
        ));
    }
}
