<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserMassAssignmentTest extends TestCase {
    use RefreshDatabase;

    protected bool $seed = true;

    public function test_request_data_cannot_make_a_user_an_admin(): void {
        $user = User::create([
            'name' => 'Member',
            'email' => 'member@example.com',
            'password' => bcrypt('password'),
            'role_id' => 2,
        ]);

        $this->assertFalse($user->fresh()->isAdmin());
    }
}
