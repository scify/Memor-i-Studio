<?php

namespace Tests\Feature;

use App\BusinessLogicLayer\managers\CardManager;
use App\BusinessLogicLayer\managers\EquivalenceSetManager;
use App\BusinessLogicLayer\managers\GameFlavorManager;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\Feature\Concerns\CreatesGameData;
use Tests\TestCase;

/**
 * Only the creator of a game or an admin may change it, its card sets and its cards.
 */
class GameFlavorAuthorizationTest extends TestCase {
    use CreatesGameData;

    use RefreshDatabase;

    protected bool $seed = true;

    private User $owner;

    private User $otherMember;

    private int $gameId;

    private int $setId;

    private int $cardId;

    protected function setUp(): void {
        parent::setUp();

        $this->owner = $this->createUser(self::MEMBER_ROLE_ID);
        $this->otherMember = $this->createUser(self::MEMBER_ROLE_ID);
        $this->createGameVersion($this->owner->id);
        $this->gameId = $this->createGame($this->owner->id);
        $this->setId = $this->createEquivalenceSet($this->gameId);
        $this->cardId = $this->createCard($this->setId);

        // The managers do the real work (builds, file copies, uploads); these tests check only who may call them.
        $this->mock(GameFlavorManager::class, fn (MockInterface $mock) => $mock->shouldIgnoreMissing());
        $this->mock(EquivalenceSetManager::class, fn (MockInterface $mock) => $mock->shouldIgnoreMissing());
        $this->mock(CardManager::class, fn (MockInterface $mock) => $mock->shouldIgnoreMissing());
    }

    public static function adminOnlyActions(): array {
        return [
            'build for testing' => ['post', '/gameFlavor/buildTest/%d'],
            'build' => ['post', '/gameFlavor/build/%d'],
            'change version page' => ['get', '/gameFlavor/changeVersionIndex/%d'],
            'change version' => ['post', '/gameFlavor/changeVersion/%d'],
        ];
    }

    #[DataProvider('adminOnlyActions')]
    public function test_the_owner_cannot_run_an_admin_action(string $method, string $url): void {
        $this->actingAs($this->owner)->{$method}(sprintf($url, $this->gameId))->assertForbidden();
    }

    #[DataProvider('adminOnlyActions')]
    public function test_an_admin_can_run_an_admin_action(string $method, string $url): void {
        $response = $this->actingAs($this->createUser(self::ADMIN_ROLE_ID))->{$method}(sprintf($url, $this->gameId));

        $this->assertNotSame(403, $response->status());
    }

    public function test_another_member_cannot_submit_the_game_for_approval(): void {
        $this->actingAs($this->otherMember)->post("/gameFlavor/{$this->gameId}/submitForApproval")->assertForbidden();
    }

    public function test_the_owner_can_submit_the_game_for_approval(): void {
        $this->actingAs($this->owner)->post("/gameFlavor/{$this->gameId}/submitForApproval")->assertRedirect();
    }

    public function test_the_owner_can_publish_a_built_game(): void {
        DB::table('game_flavor')->where('id', $this->gameId)->update(['is_built' => true]);
        $this->mock(GameFlavorManager::class, fn (MockInterface $mock) => $mock->shouldIgnoreMissing()
            ->shouldReceive('toggleGameFlavorPublishedState')->andReturn(true));

        $this->actingAs($this->owner)->post("/gameFlavor/publish/{$this->gameId}")->assertRedirect();
    }

    public function test_the_owner_cannot_publish_a_game_that_is_not_built(): void {
        $this->actingAs($this->owner)->post("/gameFlavor/publish/{$this->gameId}")->assertForbidden();
    }

    public function test_another_member_cannot_publish_or_unpublish(): void {
        DB::table('game_flavor')->where('id', $this->gameId)->update(['is_built' => true]);

        $this->actingAs($this->otherMember)->post("/gameFlavor/publish/{$this->gameId}")->assertForbidden();
        $this->actingAs($this->otherMember)->post("/gameFlavor/unpublish/{$this->gameId}")->assertForbidden();
    }

    public function test_the_owner_can_unpublish(): void {
        $this->mock(GameFlavorManager::class, fn (MockInterface $mock) => $mock->shouldIgnoreMissing()
            ->shouldReceive('toggleGameFlavorPublishedState')->andReturn(true));

        $this->actingAs($this->owner)->post("/gameFlavor/unpublish/{$this->gameId}")->assertRedirect();
    }

    public function test_another_member_cannot_open_edit_or_delete_the_game(): void {
        $this->actingAs($this->otherMember)->get("/gameFlavor/edit/{$this->gameId}")->assertForbidden();
        $this->actingAs($this->otherMember)->post("/gameFlavor/edit/{$this->gameId}")->assertForbidden();
        $this->actingAs($this->otherMember)->delete("/gameFlavor/delete/{$this->gameId}")->assertForbidden();
    }

    public function test_another_member_cannot_clone_a_private_game(): void {
        $this->actingAs($this->otherMember)->post("/gameFlavor/{$this->gameId}/clone")->assertForbidden();
    }

    public function test_another_member_can_clone_a_published_game_that_allows_it(): void {
        $publicGameId = $this->createGame($this->owner->id, published: true, allowClone: true);

        $this->actingAs($this->otherMember)->post("/gameFlavor/{$publicGameId}/clone")->assertRedirect();
    }

    public function test_another_member_cannot_add_a_card_set(): void {
        $this->actingAs($this->otherMember)
            ->post('/set/create', ['card' => [1 => ['game_flavor_id' => $this->gameId]]])
            ->assertForbidden();
    }

    public function test_another_member_cannot_edit_a_card_set(): void {
        $this->actingAs($this->otherMember)
            ->post('/equivalenceSet/edit', ['equivalence_set_id' => $this->setId])
            ->assertForbidden();
    }

    public function test_another_member_cannot_delete_a_card_set(): void {
        $this->actingAs($this->otherMember)
            ->delete("/flavor/{$this->gameId}/equivalenceSet/delete", ['id' => $this->setId])
            ->assertForbidden();
    }

    public function test_another_member_cannot_edit_a_card(): void {
        $this->actingAs($this->otherMember)
            ->post('/card/edit', ['cardId' => $this->cardId, 'card' => [1 => ['game_flavor_id' => $this->gameId]]])
            ->assertForbidden();
    }

    public function test_the_owner_can_edit_a_card_set_and_a_card(): void {
        $this->actingAs($this->owner)
            ->post('/equivalenceSet/edit', ['equivalence_set_id' => $this->setId])
            ->assertRedirect();
        $this->actingAs($this->owner)
            ->post('/card/edit', ['cardId' => $this->cardId, 'card' => [1 => ['game_flavor_id' => $this->gameId]]])
            ->assertRedirect();
    }
}
