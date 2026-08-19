<?php
declare(strict_types=1);

namespace BCOEM\Repository;

use MysqliDb;

/**
 * Base typed CRUD for a single table.
 *
 * Concrete repositories declare the Domain Row type and table name;
 * this base supplies the standard operations against MysqliDb.
 *
 * @template TRow of object
 */
abstract class AbstractRepository
{
    protected MysqliDb $db;

    public function __construct(?MysqliDb $db = null)
    {
        $this->db = $db ?? Connection::db();
    }

    /**
     * The unprefixed table name (e.g. 'brewer').
     */
    abstract protected function table(): string;

    /**
     * Map a raw assoc row to the typed Domain Row.
     *
     * @param array<string, mixed> $row
     * @return TRow
     */
    abstract protected function hydrate(array $row): object;

    /**
     * Fetch one row by primary key.
     *
     * @return TRow|null
     */
    public function get(int $id): ?object
    {
        $row = $this->db->where('id', $id)->getOne($this->table());
        if (!is_array($row) || $row === []) {
            return null;
        }
        return $this->hydrate($row);
    }

    /**
     * Fetch all rows.
     *
     * @return list<TRow>
     */
    public function all(string $orderBy = 'id ASC'): array
    {
        $rows = $this->db->orderBy($orderBy)->get($this->table());
        if (!is_array($rows)) {
            return [];
        }
        return array_map($this->hydrate(...), array_values($rows));
    }

    /**
     * Fetch rows matching a column equality.
     *
     * @return list<TRow>
     */
    public function where(string $column, int|string $value): array
    {
        $rows = $this->db->where($column, $value)->get($this->table());
        if (!is_array($rows)) {
            return [];
        }
        return array_map($this->hydrate(...), array_values($rows));
    }

    /**
     * Fetch one row matching a column equality.
     *
     * @return TRow|null
     */
    public function first(string $column, int|string $value): ?object
    {
        $row = $this->db->where($column, $value)->getOne($this->table());
        if (!is_array($row) || $row === []) {
            return null;
        }
        return $this->hydrate($row);
    }

    /**
     * Insert a row; returns the new id, or null on failure.
     *
     * @param array<string, mixed> $data
     */
    public function insert(array $data): ?int
    {
        $id = $this->db->insert($this->table(), $data);
        if ($id === false || $id === null) {
            return null;
        }
        return (int) $id;
    }

    /**
     * Update the row with the given primary key.
     *
     * @param array<string, mixed> $data
     */
    public function update(int $id, array $data): bool
    {
        return $this->db->where('id', $id)->update($this->table(), $data) === true;
    }

    /**
     * Delete the row with the given primary key.
     */
    public function delete(int $id): bool
    {
        return $this->db->where('id', $id)->delete($this->table()) === true;
    }
}
