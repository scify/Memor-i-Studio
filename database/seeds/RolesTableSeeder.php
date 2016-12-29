<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('role')->delete();
        //insert some dummy records
        DB::table('role')->insert(array(
            array('id'=> 1, 'name'=>'User','description'=>'The plain user. Can create game versions'),
            array('id'=> 2, 'name'=>'Admin','description'=>'Admin with full right to create, modify and delete game versions'),
        ));
    }
}
