<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\DB;
use RuntimeException;
use Tests\TestCase;

class TestDatabaseGuardTest extends TestCase {
    public function test_in_memory_sqlite_is_accepted(): void {
        $this->ensureTestDatabaseIsInMemory();

        $this->addToAssertionCount(1);
    }

    public function test_cached_mysql_default_connection_is_refused(): void {
        config(['database.default' => 'mysql', 'database.connections.mysql.host' => '127.0.0.1', 'database.connections.mysql.port' => '1']);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Tests must use SQLite in memory');

        $this->ensureTestDatabaseIsInMemory();
    }

    public function test_database_url_pointing_to_mysql_is_refused(): void {
        config(['database.connections.sqlite.url' => 'mysql://user:secret@127.0.0.1:1/memori']);
        DB::purge('sqlite');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Tests must use SQLite in memory');

        $this->ensureTestDatabaseIsInMemory();
    }
}
