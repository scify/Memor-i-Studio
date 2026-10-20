<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppVersionTest extends TestCase {
    use RefreshDatabase;

    protected bool $seed = true;

    public function test_the_app_version_comes_from_package_json(): void {
        $this->assertSame($this->packageVersion(), config('app.version'));
    }

    public function test_the_footer_shows_the_app_version(): void {
        $this->get('/')->assertOk()->assertSee('version <b>' . $this->packageVersion() . '</b>', false);
    }

    private function packageVersion(): string {
        return json_decode(file_get_contents(base_path('package.json')), true, flags: JSON_THROW_ON_ERROR)['version'];
    }
}
