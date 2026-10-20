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
            'home' => ['/', 'home'],
            'home alias' => ['/home', 'home'],
            'about' => ['/about', 'about'],
            'all games' => ['/games', 'game_flavor.all_games'],
            'contact' => ['/contact', 'contact'],
            'login' => ['/login', 'auth.login'],
            'register' => ['/register', 'auth.register'],
        ];
    }

    public static function memberPages(): array {
        return [
            'select game version' => ['/gameFlavor/selectVersion', 'game_flavor.forms.select_game_version'],
        ];
    }

    public static function administrationPages(): array {
        return [
            'platform statistics' => ['/administration/platform-statistics', 'admin.platform-statistics'],
            'game versions' => ['/gameVersions/all', 'game_version.list'],
            'create game version' => ['/administration/gameVersion/create', 'game_version.create_edit_index'],
            'games submitted for approval' => ['/administration/gameFlavors/submittedForApproval', 'game_flavor.list'],
            'user reports' => ['/administration/gameFlavors/userReports', 'game_flavor_report.list_all'],
        ];
    }

    #[DataProvider('publicPages')]
    public function test_guest_can_open_public_page(string $url, string $view): void {
        $this->get($url)->assertOk()->assertViewIs($view);
    }

    public function test_guest_can_load_the_game_list(): void {
        $this->post('/games/flavors')->assertOk()->assertSee(__('messages.no_games_found'), false);
    }

    #[DataProvider('memberPages')]
    #[DataProvider('administrationPages')]
    public function test_guest_is_redirected_to_login(string $url, string $view): void {
        $this->get($url)->assertRedirect('/login');
    }

    #[DataProvider('memberPages')]
    public function test_member_can_open_member_page(string $url, string $view): void {
        $this->actingAs($this->createUser(self::MEMBER_ROLE_ID))->get($url)->assertOk()->assertViewIs($view);
    }

    #[DataProvider('administrationPages')]
    public function test_member_cannot_open_administration_page(string $url, string $view): void {
        $this->actingAs($this->createUser(self::MEMBER_ROLE_ID))->get($url)->assertForbidden();
    }

    #[DataProvider('administrationPages')]
    public function test_admin_can_open_administration_page(string $url, string $view): void {
        $this->actingAs($this->createUser(self::ADMIN_ROLE_ID))->get($url)->assertOk()->assertViewIs($view);
    }

    private function createUser(int $roleId): User {
        return User::forceCreate([
            'name' => 'Test user',
            'email' => uniqid('user-') . '@example.com',
            'password' => bcrypt('password'),
            'role_id' => $roleId,
            'logout' => false,
        ]);
    }
}
