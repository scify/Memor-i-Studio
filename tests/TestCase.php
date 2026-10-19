<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;
use RuntimeException;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    /**
     * The database traits wipe the database, and any test can write to it.
     * DDEV container variables can point the tests at a real database, so
     * refuse anything other than SQLite in memory before the traits run.
     */
    protected function setUpTraits()
    {
        $this->ensureTestDatabaseIsInMemory();

        return parent::setUpTraits();
    }

    protected function ensureTestDatabaseIsInMemory(): void
    {
        $connection = DB::connection();
        $driver = $connection->getConfig('driver');
        $database = $connection->getConfig('database');

        if ($driver !== 'sqlite' || $database !== ':memory:') {
            throw new RuntimeException(
                "Tests must use SQLite in memory, not [{$driver}] database [{$database}]. "
                .'Check the DB_* lines in phpunit.xml.'
            );
        }
    }
}
