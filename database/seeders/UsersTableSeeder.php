<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class UsersTableSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $plainPassword = config('app.default_admin_password_for_seed');
        if (empty($plainPassword)) {
            throw new RuntimeException('Set DEFAULT_ADMIN_USER_PASSWORD_FOR_SEED in .env before you seed the users.');
        }
        $password = Hash::make($plainPassword);

        DB::table('users')->delete();
        DB::table('users')->insert([
            ['id'=> 1, 'name'=>'Paul Isaris', 'role_id' => 2, 'email'=>'paul@scify.org', 'password' => $password],
            ['id'=> 2, 'name'=>'Alex Tzoumas', 'role_id' => 2, 'email'=>'a.tzoumas@scify.org', 'password' => $password],
            ['id'=> 3, 'name'=>'Scify Tester', 'role_id' => 2, 'email'=>'tiasonidis@scify.org', 'password' => $password],
        ]);
    }
}
