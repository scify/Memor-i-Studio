<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();
        DB::table('users')->insert(array(
            array('id'=> 1, 'name'=>'Paul Isaris', 'role_id' => 2, 'email'=>'paul@scify.org', 'password' => '$2y$10$AB2Q2QgPWuMXyVis.EgUau2F2TZK25lFe6SB/LyEMFzL38Qqjgy1e'),
            array('id'=> 2, 'name'=>'Alex Tzoumas', 'role_id' => 2, 'email'=>'a.tzoumas@scify.org', 'password' => '$2y$10$AB2Q2QgPWuMXyVis.EgUau2F2TZK25lFe6SB/LyEMFzL38Qqjgy1e'),
            array('id'=> 3, 'name'=>'Scify Tester', 'role_id' => 2, 'email'=>'tiasonidis@scify.org', 'password' => '$2y$10$AB2Q2QgPWuMXyVis.EgUau2F2TZK25lFe6SB/LyEMFzL38Qqjgy1e')
        ));
    }
}
