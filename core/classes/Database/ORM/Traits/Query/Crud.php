<?php

declare(strict_types=1);

namespace Database\ORM\Traits\Query;

use Database\ORM\Query;
use Model;

/**
 * @mixin Query
 */
trait Crud
{
    /**
     * Insert a new record і повернути новий Model.
     */
    public function create(array $data): mixed
    {
        $cols = implode('`,`', array_keys($data));
        $phs = implode(',', array_fill(0, count($data), '?'));

        Model::db()->query(
            "INSERT INTO {$this->table} (`{$cols}`) VALUES ({$phs})",
            array_values($data)
        );

        $id = Model::db()->lastId();
        $data[$this->primaryKey] = $id;

        return new $this->modelClass($data);
    }

    /**
     * Delete a record by primary key.
     */
    public function delete(mixed $id): bool
    {
        return !Model::db()
            ->query("DELETE FROM {$this->table} WHERE `{$this->primaryKey}` = ?", [$id])
            ->error();
    }

    /**
     * Decrement a numeric column.
     */
    public function decrement(string $col, int $amt, mixed $id): bool
    {
        return $this->increment($col, -$amt, $id);
    }

    /**
     * Increment a numeric column.
     */
    public function increment(string $col, int $amt, mixed $id): bool
    {
        $sql = "UPDATE {$this->table}
                SET `{$col}` = `{$col}` + ?
                WHERE `{$this->primaryKey}` = ?";
        return !Model::db()->query($sql, [$amt, $id])->error();
    }

    /**
     * Update a record by primary key.
     */
    public function update(array $data, mixed $id): bool
    {
        $set = implode(',', array_map(fn($c) => "`{$c}` = ?", array_keys($data)));
        $sql = "UPDATE {$this->table} SET {$set} WHERE `{$this->primaryKey}` = ?";
        return !Model::db()->query($sql, [...array_values($data), $id])->error();
    }
}
