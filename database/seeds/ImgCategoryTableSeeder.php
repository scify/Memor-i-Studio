<?php

use Illuminate\Database\Seeder;

class ImgCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('image_category')->delete();
        //insert some dummy records
        DB::table('image_category')->insert(array(
            array('category'=>'game_cover','description'=>'The cover image for the game'),
            array('category'=>'card_images','description'=>'Each card is associated with 1 or 2 images')
        ));
    }
}