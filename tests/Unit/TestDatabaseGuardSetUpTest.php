<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use RuntimeException;
use Tests\TestCase;

/**
 * Proves that setUp() refuses a real database before RefreshDatabase runs
 * migrate:fresh. Nothing listens on 127.0.0.1:1, so without the guard the
 * migration fails fast with a QueryException instead of the guard's message.
 */
class TestDatabaseGuardSetUpTest extends TestCase
{
    use RefreshDatabase;

    private ?RuntimeException $setUpException = null;

    public function createApplication()
    {
        $app = parent::createApplication();

        $app['config']->set([
            'database.default' => 'mysql',
            'database.connections.mysql.host' => '127.0.0.1',
            'database.connections.mysql.port' => '1',
        ]);

        return $app;
    }

    protected function setUp(): void
    {
        try {
            parent::setUp();
        } catch (RuntimeException $exception) {
            $this->setUpException = $exception;
        }
    }

    public function test_set_up_refuses_a_mysql_connection_before_migrating(): void
    {
        $this->assertNotNull($this->setUpException);
        $this->assertStringStartsWith('Tests must use SQLite in memory, not [mysql]', $this->setUpException->getMessage());
    }
}
