<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Concerns\CreatesGameData;
use Tests\TestCase;

class GameFlavorReportTest extends TestCase {
    use CreatesGameData;

    use RefreshDatabase;

    protected bool $seed = true;

    public function test_a_report_keeps_the_comment_name_and_email(): void {
        $member = $this->createUser(self::MEMBER_ROLE_ID);
        $this->createGameVersion($member->id);
        $gameId = $this->createGame($member->id, published: true);

        $this->actingAs($member)->post('/gameFlavor/report', [
            'game_flavor_id' => $gameId,
            'user_name' => 'Reporter',
            'user_email' => 'reporter@example.com',
            'user_comment' => 'The sounds do not play.',
        ])->assertRedirect()->assertSessionHasNoErrors();

        $this->assertDatabaseHas('game_flavor_report', [
            'game_flavor_id' => $gameId,
            'user_id' => $member->id,
            'user_name' => 'Reporter',
            'user_email' => 'reporter@example.com',
            'user_comment' => 'The sounds do not play.',
        ]);
    }

    public function test_a_report_needs_a_valid_email_and_an_existing_game(): void {
        $member = $this->createUser(self::MEMBER_ROLE_ID);

        $this->actingAs($member)->post('/gameFlavor/report', [
            'game_flavor_id' => 999,
            'user_email' => 'not an email',
            'user_comment' => 'Comment',
        ])->assertSessionHasErrors(['game_flavor_id', 'user_email']);
    }
}
