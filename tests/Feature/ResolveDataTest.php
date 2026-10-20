<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Tests\Feature\Concerns\UsesTemporaryStorage;
use Tests\TestCase;

class ResolveDataTest extends TestCase {
    use UsesTemporaryStorage;

    protected function setUp(): void {
        parent::setUp();

        $this->useTemporaryStorage();
        File::ensureDirectoryExists(storage_path('app/game_versions/img'));
        File::put(storage_path('app/game_versions/img/card.txt'), 'card data');
        File::put(storage_path('app/backup.sql'), 'secret');
    }

    public function test_a_base_version_file_is_served(): void {
        $this->get('/resolveData/game_versions/img/card.txt')
            ->assertOk()
            ->assertSee('card data');
    }

    public function test_a_missing_file_answers_not_found(): void {
        $this->get('/resolveData/game_versions/img/missing.txt')->assertNotFound();
    }

    public function test_a_file_outside_the_known_folders_answers_not_found(): void {
        $this->get('/resolveData/backup.sql')->assertNotFound();
    }

    public function test_a_path_outside_storage_app_is_refused(): void {
        $this->get('/resolveData/../../composer.json')->assertNotFound();
        $this->get('/resolveData/game_versions/img/..%2F..%2F..%2Fcomposer.json')->assertNotFound();
    }

    public function test_uploads_in_these_tests_go_to_the_temporary_folder(): void {
        $this->assertStringStartsWith(storage_path('app'), Storage::disk('local')->path('x'));
        $this->assertStringStartsWith(sys_get_temp_dir(), Storage::disk('local')->path('x'));
    }
}
