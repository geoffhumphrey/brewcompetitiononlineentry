<?php

declare(strict_types=1);

namespace BCOEM\Tests\Unit;

use BCOEM\Tests\Integration\MySqlTestCase;

/**
 * Domain-core scoring tests for best_brewer_points() (lib/common.lib.php).
 *
 * best_brewer_points() calls total_paid_received() (a DB query) at the top
 * regardless of $method. To unit-test the pure arithmetic, this file loads
 * common.lib.php inside a test namespace where a stubbed total_paid_received()
 * shadows the global one for the unqualified call.
 *
 * CoA method ($method == 1): points += ((tc_entries - place) / tc_entries)^3
 * per placed entry. This defends the place-points scoring contract (4.2).
 *
 * NOTE: the unqualified total_paid_received() call inside best_brewer_points()
 * resolves to the GLOBAL function, so the namespace stub cannot shadow it.
 * Instead, this test requires site/config.php (as the legacy function does)
 * and points it at the CI MySQL service. It skips when no DB is available,
 * same gate as the repository integration tests.
 */
final class BestBrewerPointsTest extends MySqlTestCase
{
    protected function setUp(): void
    {
        parent::setUp(); // skips when no MySQL

        if (!defined('LIB')) {
            define('LIB', dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR);
        }
        if (!defined('INCLUDES')) {
            define('INCLUDES', dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR);
        }
        if (!defined('CONFIG')) {
            define('CONFIG', dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'site' . DIRECTORY_SEPARATOR);
        }
        foreach (['HOSTED', 'NHC', 'SINGLE', 'EVALUATION'] as $const) {
            if (!defined($const)) {
                define($const, false);
            }
        }
        // Write a minimal site/config.php pointing at the test DB so the
        // legacy DB-dependent calls inside common.lib.php work. Real
        // config.php (if present) is backed up and restored after the test.
        $this->writeTestConfig();
        require_once LIB . 'common.lib.php';
    }

    protected function tearDown(): void
    {
        $this->restoreConfig();
    }

    private function writeTestConfig(): void
    {
        $config = CONFIG . 'config.php';
        // NEVER clobber a real installation config. Only proceed when there
        // is no config.php (CI checkout) — otherwise skip.
        if (file_exists($config)) {
            $ci = getenv('CI') !== false;
            if (!$ci) {
                $this->markTestSkipped('site/config.php exists; refusing to overwrite a real install config');
            }
            $this->backupConfig = file_get_contents($config);
        }
        $host = getenv('BCOEM_TEST_DB_HOST') ?: '127.0.0.1';
        $user = getenv('BCOEM_TEST_DB_USER') ?: 'root';
        $pass = getenv('BCOEM_TEST_DB_PASS') ?: 'root';
        $name = getenv('BCOEM_TEST_DB_NAME') ?: 'bcoem_test';
        file_put_contents($config, <<<PHP
<?php
\$hostname = '{$host}';
\$username = '{$user}';
\$password = '{$pass}';
\$database = '{$name}';
\$database_port = 3306;
\$connection = new mysqli(\$hostname, \$username, \$password, \$database, \$database_port);
mysqli_set_charset(\$connection, 'utf8mb4');
\$brewing = \$connection;
// Empty prefix: MysqliDb's static prefix (set by MySqlTestCase::db() to
// 'baseline_') is applied to rawQueryOne() table names. Setting it here too
// would double-prefix (baseline_baseline_brewing).
\$prefix = '';
\$installation_id = 'test';
\$session_expire_after = 30;
\$setup_free_access = FALSE;
\$sub_directory = '';
\$base_url = 'http://localhost/';
\$server_root = dirname(__DIR__);
PHP);
    }

    private function restoreConfig(): void
    {
        $config = CONFIG . 'config.php';
        if ($this->backupConfig !== null) {
            file_put_contents($config, $this->backupConfig);
        } else {
            @unlink($config);
        }
    }

    private ?string $backupConfig = null;

    public function testCoaMethodSingleFirstPlace(): void
    {
        // 10 entries in the category; the brewer placed 1st.
        // points = ((10 - 1) / 10)^3 = (0.9)^3 = 0.729
        $points = best_brewer_points(1, [1], [0], [10], [], '1');
        $this->assertSame(0.729, round($points, 3));
    }

    public function testCoaMethodAllEntriesWin(): void
    {
        // 5 entries; brewer took every place 1..5 → sum of ((5-p)/5)^3.
        // 1st: (4/5)^3=0.512, 2nd: (3/5)^3=0.216, 3rd: (2/5)^3=0.064,
        // 4th: (1/5)^3=0.008, 5th: (0/5)^3=0 → total 0.8
        $places = [1, 1, 1, 1, 1];
        $points = best_brewer_points(1, $places, [0], [5], [], '1');
        $this->assertSame(0.8, round($points, 3));
    }

    public function testCoaMethodThirdPlaceFromTen(): void
    {
        // 10 entries; placed 3rd → ((10-3)/10)^3 = (0.7)^3 = 0.343
        $points = best_brewer_points(1, [0, 0, 1], [0], [10], [], '1');
        $this->assertSame(0.343, round($points, 3));
    }

    public function testCoaMethodLastPlaceIsZero(): void
    {
        // 10 entries; placed 10th (last) → ((10-10)/10)^3 = 0
        $points = best_brewer_points(1, [0, 0, 0, 0, 0, 0, 0, 0, 0, 1], [0], [10], [], '1');
        $this->assertSame(0.0, round($points, 3));
    }
}
