<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class GameListVisibilityTest extends TestCase {
    use RefreshDatabase;

    protected bool $seed = true;

    private const ADMIN_ID = 1; // created by UsersTableSeeder

    protected function setUp(): void {
        parent::setUp();

        DB::table('game_version')->insert(['id' => 1, 'name' => 'Version', 'version_code' => '1', 'data_pack_dir_name' => 'pack', 'description' => '', 'creator_id' => self::ADMIN_ID, 'cover_img_path' => '', 'online' => 0]);
        DB::table('resource_category')->insert(['id' => 1, 'path' => 'img/', 'description' => '', 'game_version_id' => 1]);
        DB::table('resource')->insert(['id' => 1, 'category_id' => 1]);
        $this->createGame('Published game', published: true, international: false);
        $this->createGame('Unpublished game', published: false, international: false);
        $this->createGame('Unpublished international game', published: false, international: true);
    }

    public function test_guest_sees_only_published_games_even_with_an_admin_id_in_the_body(): void {
        $this->post('/games/flavors', ['user' => ['id' => self::ADMIN_ID]])
            ->assertOk()
            ->assertSee('Published game')
            ->assertDontSee('Unpublished game')
            ->assertDontSee('Unpublished international game');
    }

    public function test_guest_language_filter_keeps_unpublished_games_hidden(): void {
        $this->post('/games/flavors', ['language_id' => 1])
            ->assertOk()
            ->assertSee('Published game')
            ->assertDontSee('Unpublished international game');
    }

    public function test_admin_sees_unpublished_games(): void {
        $this->actingAs(User::findOrFail(self::ADMIN_ID))
            ->post('/games/flavors')
            ->assertOk()
            ->assertSee('Unpublished game');
    }

    private function createGame(string $name, bool $published, bool $international): void {
        DB::table('game_flavor')->insert([
            'name' => $name, 'description' => '', 'lang_id' => 1, 'interface_lang_id' => 1, 'game_version_id' => 1,
            'creator_id' => self::ADMIN_ID, 'cover_img_id' => 1, 'published' => $published,
            'internationally_available' => $international, 'is_default' => 0, 'created_at' => now(),
        ]);
    }
}
