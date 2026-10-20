<?php

namespace Tests\Feature;

use App\BusinessLogicLayer\managers\CardManager;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use Tests\TestCase;

class CardEditTest extends TestCase {
    use RefreshDatabase;

    protected bool $seed = true;

    public function test_a_failed_card_edit_redirects_back_with_an_error_message(): void {
        $this->mock(CardManager::class, function (MockInterface $mock) {
            $mock->shouldReceive('editCard')->andReturn(null);
        });
        $member = User::create([
            'name' => 'Test user',
            'email' => 'member@example.com',
            'password' => bcrypt('password'),
            'role_id' => 1,
            'logout' => false,
        ]);

        $this->actingAs($member)
            ->from('/flavor/1/cards')
            ->post('/card/edit', ['card' => [1 => ['game_flavor_id' => 1]]])
            ->assertRedirect('/flavor/1/cards')
            ->assertSessionHas('flash_message_failure');
    }
}
