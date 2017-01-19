<?php

use Illuminate\Database\Seeder;

class ResourceCategoryTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('resource_category_type')->delete();
        //insert some dummy records
        DB::table('resource_category_type')->insert(array(
            array('id'=> 1, 'description'=>'Static resources (resources that refer to the game screens, tutorial, etc)'),
            array('id'=> 2, 'description'=>'Dynamic resources (resources that refer to the game dynamic models (cards, etc)'),
        ));
    }
}
