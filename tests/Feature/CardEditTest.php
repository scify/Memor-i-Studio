<?php

namespace Tests\Feature;

use App\BusinessLogicLayer\managers\CardManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use Tests\Feature\Concerns\CreatesGameData;
use Tests\TestCase;

class CardEditTest extends TestCase {
    use CreatesGameData;

    use RefreshDatabase;

    protected bool $seed = true;

    public function test_a_failed_card_edit_redirects_back_with_an_error_message(): void {
        $this->mock(CardManager::class, function (MockInterface $mock) {
            $mock->shouldReceive('editCard')->andReturn(null);
        });
        $member = $this->createUser(self::MEMBER_ROLE_ID);
        $this->createGameVersion($member->id);
        $gameId = $this->createGame($member->id);
        $cardId = $this->createCard($this->createEquivalenceSet($gameId));

        $this->actingAs($member)
            ->from('/flavor/1/cards')
            ->post('/card/edit', ['cardId' => $cardId, 'card' => [1 => ['game_flavor_id' => $gameId]]])
            ->assertRedirect('/flavor/1/cards')
            ->assertSessionHas('flash_message_failure');
    }
}
