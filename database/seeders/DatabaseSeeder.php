<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $this->call(LanguagesTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(ResourceCategoryTypeTableSeeder::class);
        // UsersTableSeeder replaces every user with the staff admins; never run it outside development.
        if (app()->environment(['local', 'testing'])) {
            $this->call(UsersTableSeeder::class);
        }
    }
}
