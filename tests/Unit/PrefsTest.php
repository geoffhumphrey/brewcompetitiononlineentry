<?php

declare(strict_types=1);

namespace BCOEM\Tests\Unit;

use BCOEM\Session\Prefs;
use PHPUnit\Framework\TestCase;

/**
 * Domain-core test for the typed preferences session accessor (Phase 2.2).
 *
 * Prefs is a typed getter/setter over the same $_SESSION['prefsFoo'] store
 * the legacy loader uses. The contract: values written via the typed setters
 * are readable back via the typed getters AND via the raw legacy
 * $_SESSION['prefsFoo'] key, so unconverted code keeps working.
 */
final class PrefsTest extends TestCase
{
    protected function setUp(): void
    {
        // Isolate the session store per test.
        foreach (array_keys($_SESSION) as $k) {
            unset($_SESSION[$k]);
        }
    }

    public function testIntRoundTrip(): void
    {
        Prefs::setEntryLimit(150);
        $this->assertSame(150, Prefs::entryLimit());
        $this->assertSame(150, $_SESSION['prefsEntryLimit'] ?? null);
    }

    public function testStringRoundTrip(): void
    {
        Prefs::setEmailHost('smtp.example.com');
        $this->assertSame('smtp.example.com', Prefs::emailHost());
        $this->assertSame('smtp.example.com', $_SESSION['prefsEmailHost'] ?? null);
    }

    public function testUnsetKeyReturnsNull(): void
    {
        $this->assertNull(Prefs::entryLimit());
        $this->assertNull(Prefs::emailHost());
    }

    public function testEmptyStringIntBecomesNull(): void
    {
        $_SESSION['prefsEntryLimit'] = '';
        $this->assertNull(Prefs::entryLimit());
    }

    public function testNumericStringIntIsCasted(): void
    {
        $_SESSION['prefsEntryLimit'] = '75';
        $this->assertSame(75, Prefs::entryLimit());
    }

    public function testFloatRoundTrip(): void
    {
        Prefs::setTimeZone(-5.000);
        $this->assertSame(-5.0, Prefs::timeZone());
        $this->assertSame(-5.0, $_SESSION['prefsTimeZone']);
    }

    public function testLegacyWrittenValueReadableByTypedGetter(): void
    {
        // Simulate the legacy loader writing raw session keys.
        $_SESSION['prefsEmailPort'] = '587';
        $this->assertSame('587', Prefs::emailPort());
    }

    public function testEnumStyleValues(): void
    {
        Prefs::setSef('Y');
        $this->assertSame('Y', Prefs::sef());
    }

    public function testUnknownKeyFailsStatically(): void
    {
        // This documents intent: calling a nonexistent key is a PHPStan
        // error at analysis time; at runtime it's a method-not-found Error.
        $this->expectException(\Error::class);
        /** @phpstan-ignore-next-line deliberately calling a nonexistent method */
        Prefs::nonexistentKey();
    }
}
