<?php

namespace Tests\Feature;

use App\BusinessLogicLayer\managers\ResourceManager;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\Feature\Concerns\CreatesGameData;
use Tests\TestCase;

class ResourceAuthorizationTest extends TestCase {
    use CreatesGameData;

    use RefreshDatabase;

    protected bool $seed = true;

    private User $owner;

    private User $otherMember;

    private int $gameId;

    protected function setUp(): void {
        parent::setUp();

        $this->owner = $this->createUser(self::MEMBER_ROLE_ID);
        $this->otherMember = $this->createUser(self::MEMBER_ROLE_ID);
        $this->createGameVersion($this->owner->id);
        $this->gameId = $this->createGame($this->owner->id);
        // The manager stores files; these tests check only who may call it.
        $this->mock(ResourceManager::class, fn (MockInterface $mock) => $mock->shouldIgnoreMissing());
    }

    public function test_another_member_cannot_replace_the_game_sounds(): void {
        $this->actingAs($this->otherMember)
            ->post('/gameResources/update', ['game_flavor_id' => $this->gameId, 'resources' => []])
            ->assertForbidden();
    }

    public function test_the_owner_can_replace_the_game_sounds(): void {
        $this->actingAs($this->owner)
            ->post('/gameResources/update', ['game_flavor_id' => $this->gameId, 'resources' => []])
            ->assertRedirect()
            ->assertSessionHas('flash_message_success');
    }

    public function test_the_game_id_must_be_an_integer(): void {
        $this->actingAs($this->owner)
            ->post('/gameResources/update', ['game_flavor_id' => $this->gameId . '/../../game_versions', 'resources' => []])
            ->assertSessionHasErrors('game_flavor_id');
    }

    public function test_another_member_cannot_open_the_game_sounds_page(): void {
        $this->actingAs($this->otherMember)->get("/gameFlavor/{$this->gameId}/resources")->assertForbidden();
    }

    public static function baseVersionRequests(): array {
        return [
            'version list' => ['get', '/gameVersions/all'],
            'version resources' => ['get', '/gameVersion/1/resources'],
            'version resources for a language' => ['get', '/gameVersion/resourcesForLanguage?game_version_id=1&lang_id=1'],
            'add language page' => ['get', '/gameVersion/1/addLanguage'],
            'add language' => ['post', '/gameVersion/addLanguage'],
            'update translations' => ['post', '/gameVersion/updateResources'],
        ];
    }

    #[DataProvider('baseVersionRequests')]
    public function test_a_member_cannot_change_or_open_base_version_admin_pages(string $method, string $url): void {
        $this->actingAs($this->owner)->{$method}($url)->assertForbidden();
    }

    public function test_an_admin_can_open_the_base_version_pages(): void {
        $admin = $this->createUser(self::ADMIN_ROLE_ID);

        $this->actingAs($admin)->get('/gameVersions/all')->assertOk();
        $this->actingAs($admin)->get('/gameVersion/1/addLanguage')->assertOk();
    }
}
