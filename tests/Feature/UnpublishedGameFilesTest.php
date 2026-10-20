<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Tests\Feature\Concerns\CreatesGameData;
use Tests\Feature\Concerns\UsesTemporaryStorage;
use Tests\TestCase;

/**
 * Files of a game are public once it is published; before that only its creator and admins may read them.
 */
class UnpublishedGameFilesTest extends TestCase {
    use CreatesGameData;

    use RefreshDatabase;

    use UsesTemporaryStorage;

    protected bool $seed = true;

    private User $owner;

    private int $privateGameId;

    private int $publicGameId;

    protected function setUp(): void {
        parent::setUp();

        $this->useTemporaryStorage();
        $this->owner = $this->createUser(self::MEMBER_ROLE_ID);
        $this->createGameVersion($this->owner->id);
        $this->privateGameId = $this->createGame($this->owner->id);
        $this->publicGameId = $this->createGame($this->owner->id, published: true);
        foreach ([$this->privateGameId, $this->publicGameId] as $gameId) {
            $directory = storage_path("app/data_packs/additional_pack_{$gameId}");
            File::ensureDirectoryExists($directory . '/Output');
            File::put($directory . '/card.txt', 'card data');
            File::put($directory . '/memori.jar', 'jar');
            File::put($directory . '/Output/memor-i-setup.exe', 'exe');
        }
    }

    public function test_a_guest_cannot_read_files_of_an_unpublished_game(): void {
        $this->get("/resolveData/data_packs/additional_pack_{$this->privateGameId}/card.txt")->assertNotFound();
        $this->get("/flavor/{$this->privateGameId}/downloadWin")->assertNotFound();
        $this->get("/flavor/{$this->privateGameId}/downloadLin")->assertNotFound();
    }

    public function test_the_creator_can_read_files_of_an_unpublished_game(): void {
        $this->actingAs($this->owner)->get("/resolveData/data_packs/additional_pack_{$this->privateGameId}/card.txt")->assertOk();
        $this->actingAs($this->owner)->get("/flavor/{$this->privateGameId}/downloadLin")->assertOk();
    }

    public function test_a_guest_can_read_files_of_a_published_game(): void {
        $this->get("/resolveData/data_packs/additional_pack_{$this->publicGameId}/card.txt")->assertOk()->assertSee('card data');
        $this->get("/flavor/{$this->publicGameId}/downloadWin")->assertOk();
    }

    public function test_a_published_game_path_cannot_reach_an_unpublished_game(): void {
        $this->get("/resolveData/data_packs/additional_pack_{$this->publicGameId}/../additional_pack_{$this->privateGameId}/card.txt")->assertNotFound();
    }

    public function test_a_null_byte_answers_not_found(): void {
        $this->get('/resolveData/data_packs/x%00.png')->assertNotFound();
    }
}
