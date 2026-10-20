<?php

namespace Tests\Feature;

use App\BusinessLogicLayer\managers\GameFlavorManager;
use App\Models\GameFlavor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\Feature\Concerns\CreatesGameData;
use Tests\TestCase;

class GameCloneTest extends TestCase {
    use CreatesGameData;

    use RefreshDatabase;

    protected bool $seed = true;

    public function test_a_clone_starts_unpublished_unbuilt_and_not_submitted(): void {
        $owner = $this->createUser(self::MEMBER_ROLE_ID);
        $otherMember = $this->createUser(self::MEMBER_ROLE_ID);
        $this->createGameVersion($owner->id);
        $gameId = $this->createGame($owner->id, published: true, allowClone: true);
        DB::table('game_flavor')->where('id', $gameId)->update(['is_built' => true, 'submitted_for_approval' => true]);

        $clone = app(GameFlavorManager::class)->cloneGameFlavor($otherMember->id, GameFlavor::findOrFail($gameId));

        $this->assertSame($otherMember->id, $clone->creator_id);
        $this->assertFalse((bool) $clone->published);
        $this->assertFalse((bool) $clone->is_built, 'A clone must be built by an admin before it can be published.');
        $this->assertFalse((bool) $clone->submitted_for_approval);
    }
}
