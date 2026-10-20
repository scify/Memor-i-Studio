<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PageAccessTest extends TestCase {
    use RefreshDatabase;

    protected bool $seed = true;

    private const MEMBER_ROLE_ID = 1;

    private const ADMIN_ROLE_ID = 2;

    public static function publicPages(): array {
        return [
            'home' => ['/'],
            'home alias' => ['/home'],
            'about' => ['/about'],
            'all games' => ['/games'],
            'contact' => ['/contact'],
            'login' => ['/login'],
            'register' => ['/register'],
        ];
    }

    public static function memberPages(): array {
        return [
            'game versions' => ['/gameVersions/all'],
            'select game version' => ['/gameFlavor/selectVersion'],
        ];
    }

    public static function administrationPages(): array {
        return [
            'platform statistics' => ['/administration/platform-statistics'],
            'create game version' => ['/administration/gameVersion/create'],
            'games submitted for approval' => ['/administration/gameFlavors/submittedForApproval'],
            'user reports' => ['/administration/gameFlavors/userReports'],
        ];
    }

    #[DataProvider('publicPages')]
    public function test_guest_can_open_public_page(string $url): void {
        $this->get($url)->assertOk();
    }

    public function test_guest_can_load_the_game_list(): void {
        $this->post('/games/flavors')->assertOk();
    }

    #[DataProvider('memberPages')]
    #[DataProvider('administrationPages')]
    public function test_guest_is_redirected_to_login(string $url): void {
        $this->get($url)->assertRedirect('/login');
    }

    #[DataProvider('memberPages')]
    public function test_member_can_open_member_page(string $url): void {
        $this->actingAs($this->createUser(self::MEMBER_ROLE_ID))->get($url)->assertOk();
    }

    #[DataProvider('administrationPages')]
    public function test_member_cannot_open_administration_page(string $url): void {
        $this->actingAs($this->createUser(self::MEMBER_ROLE_ID))->get($url)->assertForbidden();
    }

    #[DataProvider('administrationPages')]
    public function test_admin_can_open_administration_page(string $url): void {
        $this->actingAs($this->createUser(self::ADMIN_ROLE_ID))->get($url)->assertOk();
    }

    private function createUser(int $roleId): User {
        return User::create([
            'name' => 'Test user',
            'email' => uniqid('user-') . '@example.com',
            'password' => bcrypt('password'),
            'role_id' => $roleId,
            'logout' => false,
        ]);
    }
}
