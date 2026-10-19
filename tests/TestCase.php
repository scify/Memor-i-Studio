<?php

namespace Tests;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;
use RuntimeException;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * The database traits wipe the database before each test. DDEV container
     * variables, a cached config or a DATABASE_URL can point the tests at a
     * real database, so refuse anything other than SQLite in memory before the
     * traits run.
     */
    protected function setUpTraits()
    {
        $uses = class_uses_recursive(static::class);

        if (isset($uses[RefreshDatabase::class]) || isset($uses[DatabaseMigrations::class]) || isset($uses[DatabaseTruncation::class])) {
            $this->ensureTestDatabaseIsInMemory();
        }

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
                .'Run "php artisan config:clear" and check DATABASE_URL.'
            );
        }
    }
}
