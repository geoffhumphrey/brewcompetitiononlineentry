<?php

declare(strict_types=1);

namespace BCOEM\Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * Backfill logic for the 3.1.0 competition-timestamp migration.
 *
 * Before 3.1.0, competition date fields were stored via bare strtotime()
 * (interpreted in the PHP server's default timezone) instead of the admin's
 * prefsTimeZone. normalize_competition_ts() re-interprets a stored epoch as
 * wall time in the admin's timezone and returns the correct UTC epoch.
 *
 * These tests are pure (no MySQL): they load lib/update.lib.php and
 * lib/date_time.lib.php directly and exercise the real production function.
 */
final class TimestampMigrationTest extends TestCase
{
    protected function setUp(): void
    {
        if (!defined('LIB')) {
            define('LIB', dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR);
        }
        if (!defined('INCLUDES')) {
            define('INCLUDES', dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR);
        }
        foreach (['HOSTED', 'NHC', 'SINGLE', 'EVALUATION'] as $const) {
            if (!defined($const)) {
                define($const, false);
            }
        }
        // common.lib.php includes date_time.lib.php (not require_once), so it
        // must be loaded via require_once exactly once per process — loading
        // date_time.lib.php directly here would redeclare get_timezone() when
        // another test later loads common.lib.php.
        if (!function_exists('to_utc_epoch')) {
            require_once LIB . 'common.lib.php';
        }
        if (!function_exists('normalize_competition_ts')) {
            require_once LIB . 'update.lib.php';
        }
    }

    /**
     * The migration must re-base an old-style epoch to the true UTC epoch
     * for the wall time the admin meant in their timezone.
     *
     * @dataProvider migrationProvider
     */
    public function testRebasesOldEpochToUtc(string $wallTime, float $offset, string $expectedUtcWall): void
    {
        $previous = date_default_timezone_get();
        date_default_timezone_set('UTC'); // server-default context at save time

        // Old code stored this wall time as if it were UTC (server default).
        $old = strtotime($wallTime);

        // The admin entered $wallTime in their prefsTimeZone ($offset).
        $expected = to_utc_epoch($wallTime, $offset);
        $normalized = normalize_competition_ts($old, $offset);

        self::assertNotSame($old, $normalized, 'old-style epoch should be re-based when offsets differ');
        self::assertSame($expected, $normalized);

        // Round-trip: rendering the migrated epoch in the admin TZ must
        // reproduce the wall time they entered.
        self::assertSame($expectedUtcWall, gmdate('Y-m-d H:i', (int) $normalized));

        date_default_timezone_set($previous);
    }

    public static function migrationProvider(): array
    {
        return [
            // NY winter (UTC-5, no DST): 14:00 EST == 19:00 UTC.
            'NY winter entry deadline' => ['2025-01-15 14:00', -5.0, '2025-01-15 19:00'],
            // NY summer (EDT, UTC-4 via DST): 14:00 EDT == 18:00 UTC.
            // The stored offset -5.0 must NOT be applied naively (DST bug).
            'NY summer DST entry deadline' => ['2025-06-15 14:00', -5.0, '2025-06-15 18:00'],
            // London (offset 0.0 maps to Europe/London; BST in summer = +1):
            // 10:00 BST == 09:00 UTC.
            'London summer BST' => ['2025-07-01 10:00', 0.0, '2025-07-01 09:00'],
            // Tokyo (offset 9.0 -> Asia/Tokyo, no DST): 21:00 JST == 12:00 UTC.
            'Tokyo no DST' => ['2025-03-10 21:00', 9.0, '2025-03-10 12:00'],
            // Magadan (offset 11.0 -> Asia/Magadan, no DST): 08:00 == 21:00 UTC prev day.
            'Magadan no DST' => ['2025-01-10 08:00', 11.0, '2025-01-09 21:00'],
            // Mountain (offset -7.0 -> America/Denver; MDT in summer = -6):
            // 12:00 MDT == 18:00 UTC.
            'Mountain summer DST' => ['2025-06-15 12:00', -7.0, '2025-06-15 18:00'],
        ];
    }

    /**
     * When the server default TZ equals the admin TZ, an old-style epoch is
     * already correct and must be left unchanged.
     */
    public function testUnchangedWhenServerTzMatchesAdminTz(): void
    {
        $previous = date_default_timezone_get();
        date_default_timezone_set('UTC'); // server default == admin TZ

        // Offset 0.0 maps to Europe/London: GMT in winter (no DST), so in
        // January a UTC-server-default epoch and a London-GMT admin TZ agree.
        $old = strtotime('2025-01-15 14:00');

        self::assertSame($old, normalize_competition_ts($old, 0.0));

        date_default_timezone_set($previous);
    }

    /**
     * The prefsWinnerDelay "no winner date" sentinel is not a real date and
     * must never be re-based.
     */
    public function testWinnerDelaySentinelUntouched(): void
    {
        self::assertSame(2145916800, normalize_competition_ts(2145916800, -5.0));
        self::assertSame('2145916800', normalize_competition_ts('2145916800', -5.0));
    }

    /**
     * Non-epoch values (empty, zero, short, non-numeric) pass through.
     */
    public function testInvalidAndEmptyValuesPassThrough(): void
    {
        self::assertNull(normalize_competition_ts(null, -5.0));
        self::assertSame('', normalize_competition_ts('', -5.0));
        self::assertSame(0, normalize_competition_ts(0, -5.0));
        self::assertSame('0', normalize_competition_ts('0', -5.0));
        self::assertSame(123, normalize_competition_ts(123, -5.0));
        self::assertSame('abc', normalize_competition_ts('abc', -5.0));
        self::assertSame(123456789, normalize_competition_ts(123456789, -5.0)); // 9 digits
        self::assertSame(12345678901, normalize_competition_ts(12345678901, -5.0)); // 11 digits
    }
}
