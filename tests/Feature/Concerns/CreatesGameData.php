<?php

namespace Tests\Feature\Concerns;

use App\Models\User;
use Illuminate\Support\Facades\DB;

/**
 * Minimal rows for game flavors: one game version, one cover image resource, users and games.
 */
trait CreatesGameData {
    protected const MEMBER_ROLE_ID = 1;

    protected const ADMIN_ROLE_ID = 2;

    protected function createGameVersion(int $creatorId): void {
        DB::table('game_version')->insert(['id' => 1, 'name' => 'Version', 'version_code' => '1', 'data_pack_dir_name' => 'pack', 'description' => '', 'creator_id' => $creatorId, 'cover_img_path' => '', 'online' => 0]);
        DB::table('resource_category')->insert(['id' => 1, 'path' => 'img/', 'description' => '', 'game_version_id' => 1]);
        DB::table('resource')->insert(['id' => 1, 'category_id' => 1]);
    }

    protected function createUser(int $roleId): User {
        return User::create([
            'name' => 'Test user',
            'email' => uniqid('user-') . '@example.com',
            'password' => bcrypt('password'),
            'role_id' => $roleId,
            'logout' => false,
        ]);
    }

    protected function createGame(int $creatorId, string $name = 'Game', bool $published = false, bool $international = false, bool $allowClone = false): int {
        return DB::table('game_flavor')->insertGetId([
            'name' => $name, 'description' => '', 'lang_id' => 1, 'interface_lang_id' => 1, 'game_version_id' => 1,
            'creator_id' => $creatorId, 'cover_img_id' => 1, 'published' => $published,
            'internationally_available' => $international, 'allow_clone' => $allowClone, 'is_default' => 0,
            'created_at' => now(),
        ]);
    }

    protected function createEquivalenceSet(int $gameId): int {
        return DB::table('equivalence_set')->insertGetId(['name' => 'set', 'flavor_id' => $gameId, 'created_at' => now()]);
    }

    protected function createCard(int $setId): int {
        return DB::table('card')->insertGetId(['label' => 'card', 'equivalence_set_id' => $setId, 'category' => 'item', 'created_at' => now()]);
    }
}
