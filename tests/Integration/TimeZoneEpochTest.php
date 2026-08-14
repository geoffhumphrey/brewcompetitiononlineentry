<?php

declare(strict_types=1);

namespace BCOEM\Tests\Integration;

use PHPUnit\Framework\TestCase;

/**
 * Timezone/epoch regression test for issue #1716 (PR #1718).
 *
 * The save path stores form datetimes as UTC Unix epochs so the stored value
 * is timezone-independent. Before the fix, strtotime() interpreted input in
 * the server's default TZ, so a save in a different TZ (or after a TZ
 * correction / DST transition) silently shifted the stored instant.
 *
 * The contract: to_utc_epoch($displayed, $offset) must equal the true UTC
 * epoch of that instant, and getTimeZoneDateTime() must render it back to
 * the same local datetime — a lossless round-trip in both DST states.
 *
 * This test fails on the original #1718 implementation, which subtracted the
 * stored (winter) offset again on top of strtotime()'s DST-aware result,
 * double-shifting the epoch whenever DST was in effect.
 */
final class TimeZoneEpochTest extends TestCase
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
        require_once LIB . 'date_time.lib.php';
    }

    /**
     * @return array<string, array{0: string, 1: float, 2: string}>
     */
    public static function roundTripProvider(): array
    {
        return [
            'NY summer (EDT, stored offset -5)' => ['2025-06-15 14:00', -5.000, 'America/New_York'],
            'NY winter (EST)' => ['2025-01-15 14:00', -5.000, 'America/New_York'],
            'London summer (BST, offset 0)' => ['2025-06-15 14:00', 0.000, 'Europe/London'],
            'Tokyo (offset +9, no DST)' => ['2025-06-15 14:00', 9.000, 'Asia/Tokyo'],
            'Sydney summer (AEDT, offset +10)' => ['2025-12-25 08:30', 10.000, 'Australia/Sydney'],
            'Phoenix (offset -7.001, no DST)' => ['2025-06-15 14:00', -7.001, 'America/Phoenix'],
        ];
    }

    /**
     * @dataProvider roundTripProvider
     */
    public function testStoredEpochRoundTripsLosslessly(string $local, float $offset, string $tzName): void
    {
        $epoch = to_utc_epoch($local, $offset);
        $this->assertNotFalse($epoch, 'to_utc_epoch should parse valid datetime');

        $displayed = getTimeZoneDateTime($offset, $epoch, 1, 1, 'system', 'date-time-system');
        $this->assertSame($local, $displayed, 'stored epoch must render back to the entered local datetime');
    }

    public function testStoredEpochIsTrueUtcInstant(): void
    {
        // 2025-06-15 14:00 in New York (EDT, UTC-4) is 18:00 UTC.
        $epoch = to_utc_epoch('2025-06-15 14:00', -5.000);
        date_default_timezone_set('UTC');
        $this->assertSame(strtotime('2025-06-15 18:00'), $epoch);
        // Winter: 14:00 EST (UTC-5) is 19:00 UTC.
        $epochWinter = to_utc_epoch('2025-01-15 14:00', -5.000);
        $this->assertSame(strtotime('2025-01-15 19:00'), $epochWinter);
    }

    public function testEmptyStringReturnsFalse(): void
    {
        $this->assertFalse(to_utc_epoch('', -5.000));
    }

    public function testGarbageReturnsFalse(): void
    {
        $this->assertFalse(to_utc_epoch('not-a-date', -5.000));
    }

    public function testSameLocalTimeAcrossOffsetsIsDifferentInstant(): void
    {
        // 14:00 in NY (UTC-4 in summer) is NOT the same instant as 14:00 in
        // London (UTC+1 in summer); the epochs must differ accordingly.
        $ny = to_utc_epoch('2025-06-15 14:00', -5.000);
        $london = to_utc_epoch('2025-06-15 14:00', 0.000);
        $this->assertNotSame($ny, $london);
        // NY (UTC-4 in summer) is 5h behind London (UTC+1); its local 14:00 is
        // a later absolute instant, so its epoch is 5h (18000s) larger.
        $this->assertSame(5 * 3600, $ny - $london);
    }

    public function testUtcOffsetZeroDefaultsToLondon(): void
    {
        // offset 0.000 maps to Europe/London; in summer BST (UTC+1) applies.
        $epoch = to_utc_epoch('2025-06-15 14:00', 0.000);
        date_default_timezone_set('UTC');
        $this->assertSame(strtotime('2025-06-15 13:00'), $epoch);
    }
}
