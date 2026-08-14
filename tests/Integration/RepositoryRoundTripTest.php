<?php

declare(strict_types=1);

namespace BCOEM\Tests\Integration;

use BCOEM\Domain\BrewerRow;
use BCOEM\Domain\BrewingRow;
use BCOEM\Domain\JudgingScoresRow;
use BCOEM\Domain\PreferencesRow;
use BCOEM\Domain\StylesRow;
use BCOEM\Repository\BrewerRepository;
use BCOEM\Repository\BrewingRepository;
use BCOEM\Repository\JudgingScoresRepository;
use BCOEM\Repository\PreferencesRepository;
use BCOEM\Repository\StylesRepository;

/**
 * Domain-core round-trip tests for the typed data layer (Phase 2.1).
 *
 * Each repository maps a `baseline_*` table to a readonly Domain Row class.
 * These tests prove insert -> fetch -> update -> delete round-trips preserve
 * typed values across the MysqliDb boundary, in the CI MySQL service.
 */
final class RepositoryRoundTripTest extends MySqlTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testBrewerRoundTrip(): void
    {
        $repo = new BrewerRepository(self::db());
        $id = $repo->insert([
            'brewerFirstName' => 'Ada',
            'brewerLastName' => 'Lovelace',
            'brewerBreweryName' => 'Analytical Engines Brewing',
        ]);
        $this->assertIsInt($id);

        $row = $repo->get($id);
        $this->assertInstanceOf(BrewerRow::class, $row);
        $this->assertSame('Ada', $row->brewerFirstName);
        $this->assertSame('Lovelace', $row->brewerLastName);
        $this->assertSame('Analytical Engines Brewing', $row->brewerBreweryName);

        $this->assertTrue($repo->update($id, ['brewerFirstName' => 'Augusta']));
        $updated = $repo->get($id);
        $this->assertSame('Augusta', $updated->brewerFirstName);

        $this->assertTrue($repo->delete($id));
        $this->assertNull($repo->get($id));
    }

    public function testBrewingRoundTrip(): void
    {
        $repo = new BrewingRepository(self::db());
        $id = $repo->insert([
            'brewBrewerID' => 1,
            'brewCategory' => '1',
            'brewSubCategory' => 'A',
            'brewStyle' => 'American Light Lager',
        ]);
        $this->assertIsInt($id);

        $row = $repo->get($id);
        $this->assertInstanceOf(BrewingRow::class, $row);
        $this->assertSame('1', $row->brewCategory);
        $this->assertSame('A', $row->brewSubCategory);

        $this->assertTrue($repo->update($id, ['brewStyle' => 'International Pale Lager']));
        $this->assertSame('International Pale Lager', $repo->get($id)->brewStyle);

        $this->assertTrue($repo->delete($id));
        $this->assertNull($repo->get($id));
    }

    public function testStylesRoundTrip(): void
    {
        $repo = new StylesRepository(self::db());
        $id = $repo->insert([
            'brewStyleGroup' => '1',
            'brewStyleNum' => 'A',
            'brewStyle' => 'American Light Lager',
            'brewStyleVersion' => 'BJCP2015',
        ]);
        $this->assertIsInt($id);

        $row = $repo->get($id);
        $this->assertInstanceOf(StylesRow::class, $row);
        $this->assertSame('American Light Lager', $row->brewStyle);

        $this->assertTrue($repo->update($id, ['brewStyleNum' => 'B']));
        $this->assertSame('B', $repo->get($id)->brewStyleNum);

        $this->assertTrue($repo->delete($id));
        $this->assertNull($repo->get($id));
    }

    public function testJudgingScoresRoundTrip(): void
    {
        $repo = new JudgingScoresRepository(self::db());
        $id = $repo->insert([
            'eid' => 1,
            'scoreEntry' => 38.5,
            'scorePlace' => 1,
        ]);
        $this->assertIsInt($id);

        $row = $repo->get($id);
        $this->assertInstanceOf(JudgingScoresRow::class, $row);
        $this->assertSame(1.0, $row->scorePlace);
        $this->assertSame(38.5, $row->scoreEntry);

        $this->assertTrue($repo->update($id, ['scorePlace' => 2]));
        $this->assertSame(2.0, $repo->get($id)->scorePlace);

        $this->assertTrue($repo->delete($id));
        $this->assertNull($repo->get($id));
    }

    public function testPreferencesRowHydration(): void
    {
        // The preferences table is a single-row config; hydrate a typed row
        // directly from an assoc array to assert the typed field mapping.
        $row = PreferencesRow::fromArray([
            'id' => 1,
            'prefsEntryLimit' => '100',
            'prefsTimeZone' => '-5.000',
            'prefsEmailHost' => 'smtp.example.com',
            'prefsSEF' => 'Y',
        ]);
        $this->assertInstanceOf(PreferencesRow::class, $row);
        $this->assertSame(100, $row->prefsEntryLimit);
        $this->assertSame(-5.0, $row->prefsTimeZone);
        $this->assertSame('smtp.example.com', $row->prefsEmailHost);
        $this->assertSame('Y', $row->prefsSEF);
    }
}
