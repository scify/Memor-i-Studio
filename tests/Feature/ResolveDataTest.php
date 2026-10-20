<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\File;
use Tests\TestCase;

class ResolveDataTest extends TestCase {
    private string $directory;

    protected function setUp(): void {
        parent::setUp();

        $this->directory = storage_path('app/resolve-data-test');
        File::ensureDirectoryExists($this->directory);
        File::put($this->directory . '/card.txt', 'card data');
    }

    protected function tearDown(): void {
        File::deleteDirectory($this->directory);

        parent::tearDown();
    }

    public function test_a_file_in_storage_app_is_served(): void {
        $this->get('/resolveData/resolve-data-test/card.txt')
            ->assertOk()
            ->assertSee('card data');
    }

    public function test_a_missing_file_answers_not_found(): void {
        $this->get('/resolveData/resolve-data-test/missing.txt')->assertNotFound();
    }

    public function test_a_path_outside_storage_app_is_refused(): void {
        $this->get('/resolveData/../../composer.json')->assertNotFound();
        $this->get('/resolveData/resolve-data-test/..%2F..%2F..%2Fcomposer.json')->assertNotFound();
    }
}
