<?php

namespace Tests\Feature;

use App\Utils\ServerResponses;
use Database\Seeders\GameRequestStatusTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\Feature\Concerns\CreatesGameData;
use Tests\TestCase;

/**
 * The desktop game API has no player authentication yet; the server checks what it can without it.
 */
class PlayerApiTest extends TestCase {
    use CreatesGameData;

    use RefreshDatabase;

    protected bool $seed = true;

    private int $gameRequestId;

    private const INITIATOR_ID = 1;

    private const OPPONENT_ID = 2;

    private const OUTSIDER_ID = 3;

    protected function setUp(): void {
        parent::setUp();

        $this->createGameVersion(1);
        $gameId = $this->createGame(1);
        foreach ([self::INITIATOR_ID, self::OPPONENT_ID, self::OUTSIDER_ID] as $id) {
            DB::table('player')->insert(['id' => $id, 'user_name' => "player{$id}", 'password' => bcrypt('secret'), 'in_game' => 0, 'last_seen_online' => now()]);
        }
        $this->seed(GameRequestStatusTableSeeder::class);
        $this->gameRequestId = DB::table('game_request')->insertGetId([
            'status_id' => 1, 'player_initiator_id' => self::INITIATOR_ID, 'player_opponent_id' => self::OPPONENT_ID,
            'game_flavor_id' => $gameId, 'game_level_id' => 1, 'created_at' => now(),
        ]);
    }

    public function test_a_player_of_the_game_can_send_a_movement(): void {
        $this->postJson('/api/gameMovement/create', $this->movement(self::INITIATOR_ID))
            ->assertJsonPath('code', ServerResponses::$RESPONSE_SUCCESSFUL);
    }

    public function test_a_player_outside_the_game_cannot_send_a_movement(): void {
        $this->postJson('/api/gameMovement/create', $this->movement(self::OUTSIDER_ID))
            ->assertJsonPath('code', ServerResponses::$RESPONSE_ERROR);

        $this->assertDatabaseCount('game_movement', 0);
    }

    public function test_latest_movement_needs_an_opponent_of_the_game(): void {
        $this->postJson('/api/gameMovement/latest', ['game_request_id' => $this->gameRequestId, 'opponent_id' => self::OUTSIDER_ID, 'last_timestamp' => 0])
            ->assertJsonPath('code', ServerResponses::$RESPONSE_ERROR);
    }

    public function test_ids_must_be_integers(): void {
        $this->postJson('/api/gameMovement/create', $this->movement('1 or 1=1'))
            ->assertJsonPath('code', ServerResponses::$VALIDATION_ERROR);
    }

    public function test_online_players_without_parameters_is_a_validation_error(): void {
        $this->getJson('/api/players/online')->assertOk()->assertJsonPath('code', ServerResponses::$VALIDATION_ERROR);
    }

    public function test_online_players_do_not_include_password_hashes(): void {
        $response = $this->getJson('/api/players/online?player_id=' . self::INITIATOR_ID)->assertOk();

        $this->assertStringContainsString('player2', $response->getContent());
        $this->assertStringNotContainsString('password', $response->getContent());
    }

    public function test_player_login_is_throttled(): void {
        $credentials = ['user_name' => 'player1', 'password' => 'wrong', 'game_flavor_pack_identifier' => 'x'];
        for ($i = 0; $i < 10; $i++) {
            $this->postJson('/api/player/login', $credentials)->assertOk();
        }

        $this->postJson('/api/player/login', $credentials)->assertTooManyRequests();
    }

    private function movement(int|string $playerId): array {
        return ['player_id' => $playerId, 'movement_json' => '{}', 'game_request_id' => $this->gameRequestId, 'timestamp' => 1];
    }

    public function test_player_login_is_throttled_per_address_across_user_names(): void {
        for ($i = 0; $i < 30; $i++) {
            $this->postJson('/api/player/login', ['user_name' => "player-{$i}", 'password' => 'wrong', 'game_flavor_pack_identifier' => 'x'])->assertOk();
        }

        $this->postJson('/api/player/login', ['user_name' => 'player-31', 'password' => 'wrong', 'game_flavor_pack_identifier' => 'x'])->assertTooManyRequests();
    }

    public function test_a_user_name_must_be_a_string(): void {
        $this->postJson('/api/player/login', ['user_name' => ['a'], 'password' => 'wrong', 'game_flavor_pack_identifier' => 'x'])
            ->assertOk()
            ->assertJsonPath('code', ServerResponses::$VALIDATION_ERROR);
    }
}
