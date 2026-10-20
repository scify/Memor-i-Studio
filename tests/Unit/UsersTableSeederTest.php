<?php

namespace Tests\Unit;

use Database\Seeders\UsersTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use RuntimeException;
use Tests\TestCase;

class UsersTableSeederTest extends TestCase {
    use RefreshDatabase;

    // RefreshDatabase seeds only on the first migration of the run, so every database test must seed.
    protected bool $seed = true;

    public function test_seeded_users_get_the_password_from_the_environment(): void {
        config(['app.default_admin_password_for_seed' => 'a-local-password']);

        $this->seed(UsersTableSeeder::class);

        $passwords = DB::table('users')->pluck('password');
        $this->assertNotEmpty($passwords);
        foreach ($passwords as $hash) {
            $this->assertTrue(Hash::check('a-local-password', $hash));
        }
    }

    public function test_seeding_stops_without_a_password(): void {
        config(['app.default_admin_password_for_seed' => '']);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('DEFAULT_ADMIN_USER_PASSWORD_FOR_SEED');

        $this->seed(UsersTableSeeder::class);
    }
}
