<?php

namespace Tests\Feature;

use App\BusinessLogicLayer\managers\CardManager;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Mockery\MockInterface;
use Tests\Feature\Concerns\CreatesGameData;
use Tests\TestCase;

class EquivalenceSetCreateTest extends TestCase {
    use CreatesGameData;

    use RefreshDatabase;

    protected bool $seed = true;

    public function test_a_failed_card_creation_leaves_no_card_set(): void {
        $this->mock(CardManager::class, fn (MockInterface $mock) => $mock->shouldReceive('createCards')
            ->andThrow(new Exception('Card creation failed')));
        $member = $this->createUser(self::MEMBER_ROLE_ID);
        $this->createGameVersion($member->id);
        $gameId = $this->createGame($member->id);

        $this->actingAs($member)->post('/set/create', ['card' => [1 => [
            'game_flavor_id' => $gameId,
            'image' => UploadedFile::fake()->image('card.png'),
            // Four MPEG frame headers: the mimetypes rule reads the content, not the name.
            'sound' => UploadedFile::fake()->createWithContent('card.mp3', str_repeat("\xFF\xFB\x90\x64" . str_repeat("\x00", 413), 4)),
        ]]])->assertSessionHasNoErrors()->assertSessionHas('flash_message_failure');

        $this->assertDatabaseCount('equivalence_set', 0);
    }
}
