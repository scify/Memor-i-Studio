<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\Feature\Concerns\CreatesGameData;
use Tests\TestCase;

/**
 * A link or an image on another site can trigger a GET request from a logged-in browser, so
 * actions that change data accept only POST or DELETE, which need the CSRF token.
 */
class StateChangingRoutesTest extends TestCase {
    use CreatesGameData;

    use RefreshDatabase;

    protected bool $seed = true;

    public static function stateChangingUrls(): array {
        return [
            'clone' => ['/gameFlavor/1/clone'],
            'submit for approval' => ['/gameFlavor/1/submitForApproval'],
            'delete game' => ['/gameFlavor/delete/1'],
            'publish' => ['/gameFlavor/publish/1'],
            'unpublish' => ['/gameFlavor/unpublish/1'],
            'build for testing' => ['/gameFlavor/buildTest/1'],
            'build' => ['/gameFlavor/build/1'],
            'change version' => ['/gameFlavor/changeVersion/1'],
            'delete card set' => ['/flavor/1/equivalenceSet/delete'],
            'admin delete version' => ['/administration/gameVersion/1/delete'],
            'admin build game' => ['/administration/gameFlavor/build/1'],
            'admin build version' => ['/administration/gameVersion/build/1'],
        ];
    }

    #[DataProvider('stateChangingUrls')]
    public function test_a_get_request_cannot_change_data(string $url): void {
        $this->actingAs($this->createUser(self::ADMIN_ROLE_ID))->get($url)->assertMethodNotAllowed();
    }
}
