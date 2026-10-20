<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Concerns\CreatesGameData;
use Tests\TestCase;

class EquivalenceSetPageTest extends TestCase {
    use CreatesGameData;

    use RefreshDatabase;

    protected bool $seed = true;

    public function test_the_card_set_page_passes_the_cards_and_the_edit_route_to_its_script(): void {
        $owner = $this->createUser(self::MEMBER_ROLE_ID);
        $this->createGameVersion($owner->id);
        $gameId = $this->createGame($owner->id);
        $this->createCard($this->createEquivalenceSet($gameId));

        $this->actingAs($owner)->get("/flavor/{$gameId}/cards")
            ->assertOk()
            ->assertSee('new window.EquivalenceSetsController([', false)
            ->assertSee(json_encode(route('editCard')), false)
            // The card dialog resets its form to this route after an edit; without it, the next create posted to the edit route.
            ->assertSee(json_encode(route('createEquivalenceSet')), false)
            ->assertDontSee('window.cards', false);
    }
}
