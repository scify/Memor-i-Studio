<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::table('role')->delete();
        DB::table('role')->insert([
            ['id'=> 1, 'name'=>'User', 'description'=>'The plain user. Can create game versions'],
            ['id'=> 2, 'name'=>'Admin', 'description'=>'Admin with full right to create, modify and delete game versions'],
        ]);
    }
}
