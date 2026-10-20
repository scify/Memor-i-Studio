<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ImgCategoryTableSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::table('image_category')->delete();
        // insert some dummy records
        DB::table('image_category')->insert([
            ['category'=>'game_cover', 'description'=>'The cover image for the game'],
            ['category'=>'card_images', 'description'=>'Each card is associated with 1 or 2 images'],
        ]);
    }
}
