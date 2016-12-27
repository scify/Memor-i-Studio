<?php

use Illuminate\Database\Seeder;

class LanguagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('language')->delete();
        //insert some dummy records
        DB::table('language')->insert(array(
            array('name'=>'Greek','code'=>'el','flag_img_path'=>'el.png'),
            array('name'=>'English','code'=>'en','flag_img_path'=>'en.png'),
        ));
    }
}
