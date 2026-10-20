<?php

namespace Tests\Unit;

use App\BusinessLogicLayer\managers\CardManager;
use App\Models\EquivalenceSet;
use Exception;
use Mockery;
use Tests\TestCase;

class CardManagerTest extends TestCase {
    public function test_card_creation_failure_throws_an_exception(): void {
        $manager = Mockery::mock(CardManager::class)->makePartial();
        $manager->shouldReceive('createNewCard')->andReturn(null);
        $equivalenceSet = new EquivalenceSet;
        $equivalenceSet->id = 1;

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Card creation failed');

        $manager->createCards(1, $equivalenceSet, ['card' => [1 => [], 2 => []]]);
    }
}
