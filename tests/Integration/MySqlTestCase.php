<?php

declare(strict_types=1);

namespace BCOEM\Tests\Integration;

use MysqliDb;
use PHPUnit\Framework\TestCase;

/**
 * Base for MySQL-backed integration tests.
 *
 * CI (`.github/workflows/ci.yml`) runs a MySQL 8.0 service, loads
 * `sql/bcoem_baseline_3.0.X.sql` into database `bcoem_test` (tables
 * prefixed `baseline_`), and sets env vars. Local runs without MySQL
 * skip these tests rather than fail.
 *
 * Connection details can come from env (CI) or defaults matching the
 * workflow. Table prefix is `baseline_` to match the CI schema import.
 */
abstract class MySqlTestCase extends TestCase
{
    private static ?MysqliDb $db = null;

    protected function setUp(): void
    {
        if (!self::databaseAvailable()) {
            self::markTestSkipped('MySQL not available: ' . self::$connectError);
        }
    }

    private static ?string $connectError = null;

    protected static function db(): MysqliDb
    {
        if (!self::$db instanceof \MysqliDb) {
            $host = getenv('BCOEM_TEST_DB_HOST') ?: '127.0.0.1';
            $user = getenv('BCOEM_TEST_DB_USER') ?: 'root';
            $pass = getenv('BCOEM_TEST_DB_PASS') ?: 'root';
            $name = getenv('BCOEM_TEST_DB_NAME') ?: 'bcoem_test';
            self::$db = new MysqliDb($host, $user, $pass, $name);
            self::$db->setPrefix('baseline_');
        }
        return self::$db;
    }

    protected static function databaseAvailable(): bool
    {
        // CI (GitHub Actions) sets CI=true and runs a MySQL service. Locally,
        // only attempt a connection when explicitly requested via env.
        $ci = getenv('CI') !== false;
        $requested = getenv('BCOEM_TEST_DB') === '1';
        if (!$ci && !$requested) {
            return false;
        }
        try {
            self::db()->connect();
            return true;
        } catch (\Throwable $e) {
            self::$connectError = $e->getMessage();
            return false;
        }
    }

    protected static function truncate(string $table): void
    {
        self::db()->rawQuery('TRUNCATE TABLE `baseline_' . $table . '`');
    }
}
