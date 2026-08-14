<?php
declare(strict_types=1);

namespace BCOEM\Repository;

use BCOEM\Domain\ArchiveRow;
use BCOEM\Domain\BcoemSysRow;
use BCOEM\Domain\BrewerRow;
use BCOEM\Domain\BrewingRow;
use BCOEM\Domain\ContactsRow;
use BCOEM\Domain\ContestInfoRow;
use BCOEM\Domain\DropOffRow;
use BCOEM\Domain\EvaluationRow;
use BCOEM\Domain\JudgingAssignmentsRow;
use BCOEM\Domain\JudgingFlightsRow;
use BCOEM\Domain\JudgingLocationsRow;
use BCOEM\Domain\JudgingPreferencesRow;
use BCOEM\Domain\JudgingScoresRow;
use BCOEM\Domain\JudgingScoresBosRow;
use BCOEM\Domain\JudgingTablesRow;
use BCOEM\Domain\ModsRow;
use BCOEM\Domain\PreferencesRow;
use BCOEM\Domain\SpecialBestDataRow;
use BCOEM\Domain\SpecialBestInfoRow;
use BCOEM\Domain\SponsorsRow;
use BCOEM\Domain\StaffRow;
use BCOEM\Domain\StylesRow;
use BCOEM\Domain\StyleTypesRow;
use BCOEM\Domain\UsersRow;
use MysqliDb;

/**
 * Thin typed wrapper over the legacy MysqliDb singleton.
 *
 * Keeps `mysqli` underneath (per the plan: no new runtime), exposes the
 * operations the repositories need with precise return types so PHPStan can
 * verify `$row['field']` accesses against the Domain Row classes.
 */
final class Connection
{
    private function __construct()
    {
    }

    public static function db(): MysqliDb
    {
        $db = MysqliDb::getInstance();
        if (!$db instanceof MysqliDb) {
            throw new \RuntimeException('MysqliDb has not been initialized (paths.php)');
        }
        return $db;
    }

    /**
     * Fetch one row by primary key, typed.
     *
     * @return array<string, mixed>|null
     */
    public static function one(string $table, int $id): ?array
    {
        $row = self::db()->where('id', $id)->getOne($table);
        if (!is_array($row) || $row === []) {
            return null;
        }
        return $row;
    }

    /**
     * Fetch all rows (optionally ordered), typed.
     *
     * @return list<array<string, mixed>>
     */
    public static function all(string $table, string $orderBy = 'id ASC'): array
    {
        $rows = self::db()->orderBy($orderBy)->get($table);
        if (!is_array($rows)) {
            return [];
        }
        return array_values($rows);
    }

    /**
     * Fetch rows by a single column equality, typed.
     *
     * @return list<array<string, mixed>>
     */
    public static function where(string $table, string $column, int|string $value): array
    {
        $rows = self::db()->where($column, $value)->get($table);
        if (!is_array($rows)) {
            return [];
        }
        return array_values($rows);
    }

    /**
     * Insert a row; returns the new id, or null on failure.
     *
     * @param array<string, mixed> $data
     */
    public static function insert(string $table, array $data): ?int
    {
        $id = self::db()->insert($table, $data);
        if ($id === false || $id === null) {
            return null;
        }
        return (int) $id;
    }

    /**
     * Update rows matching the given where column/value.
     * Returns affected row count (0 if none matched).
     *
     * @param array<string, mixed> $data
     */
    public static function update(string $table, array $data, string $whereColumn, int|string $whereValue): int
    {
        $status = self::db()->where($whereColumn, $whereValue)->update($table, $data);
        return $status === true ? 1 : 0;
    }

    /**
     * Delete rows matching the given where column/value.
     */
    public static function delete(string $table, string $whereColumn, int|string $whereValue): bool
    {
        return self::db()->where($whereColumn, $whereValue)->delete($table) === true;
    }

    /**
     * Map a raw assoc row to the Domain Row class for a table.
     *
     * @param array<string, mixed> $data
     */
    public static function row(string $table, array $data): object
    {
        return match ($table) {
            'archive' => ArchiveRow::fromArray($data),
            'bcoem_sys' => BcoemSysRow::fromArray($data),
            'brewer' => BrewerRow::fromArray($data),
            'brewing' => BrewingRow::fromArray($data),
            'contacts' => ContactsRow::fromArray($data),
            'contest_info' => ContestInfoRow::fromArray($data),
            'drop_off' => DropOffRow::fromArray($data),
            'evaluation' => EvaluationRow::fromArray($data),
            'judging_assignments' => JudgingAssignmentsRow::fromArray($data),
            'judging_flights' => JudgingFlightsRow::fromArray($data),
            'judging_locations' => JudgingLocationsRow::fromArray($data),
            'judging_preferences' => JudgingPreferencesRow::fromArray($data),
            'judging_scores' => JudgingScoresRow::fromArray($data),
            'judging_scores_bos' => JudgingScoresBosRow::fromArray($data),
            'judging_tables' => JudgingTablesRow::fromArray($data),
            'mods' => ModsRow::fromArray($data),
            'preferences' => PreferencesRow::fromArray($data),
            'special_best_data' => SpecialBestDataRow::fromArray($data),
            'special_best_info' => SpecialBestInfoRow::fromArray($data),
            'sponsors' => SponsorsRow::fromArray($data),
            'staff' => StaffRow::fromArray($data),
            'styles' => StylesRow::fromArray($data),
            'style_types' => StyleTypesRow::fromArray($data),
            'users' => UsersRow::fromArray($data),
            default => throw new \InvalidArgumentException("Unknown table: {$table}"),
        };
    }
}
