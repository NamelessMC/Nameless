<?php

declare(strict_types=1);

namespace Database\ORM\Traits\Query;

use Database\ORM\Query;
use Model;

/**
 * @mixin Query
 */
trait Aggregates
{
    /**
     * Get the average of a column.
     */
    public function avg(string $col): float
    {
        $sql = "SELECT AVG(`{$col}`) AS `avg` FROM {$this->table}"
            . ($this->wheres ? ' WHERE ' . implode(' AND ', $this->wheres) : '');

        return (float)Model::db()->query($sql, $this->params)->first()->avg;
    }

    /**
     * Get the count of rows.
     */
    public function count(string $col = '*'): int
    {
        $column = $col === '*' ? '*' : "`{$col}`";
        $sql = "SELECT COUNT({$column}) AS `count` FROM {$this->table}"
            . ($this->wheres ? ' WHERE ' . implode(' AND ', $this->wheres) : '');

        $row = Model::db()->query($sql, $this->params)->first();
        return (int)($row->count ?? 0);
    }

    /**
     * Get the maximum value of a column.
     */
    public function max(string $col): int
    {
        $sql = "SELECT MAX(`{$col}`) AS `max` FROM {$this->table}"
            . ($this->wheres ? ' WHERE ' . implode(' AND ', $this->wheres) : '');

        return (int)Model::db()->query($sql, $this->params)->first()->max;
    }

    /**
     * Get the minimum value of a column.
     */
    public function min(string $col): int
    {
        $sql = "SELECT MIN(`{$col}`) AS `min` FROM {$this->table}"
            . ($this->wheres ? ' WHERE ' . implode(' AND ', $this->wheres) : '');

        return (int)Model::db()->query($sql, $this->params)->first()->min;
    }

    /**
     * Get the sum of a column.
     */
    public function sum(string $col): int
    {
        $sql = "SELECT SUM(`{$col}`) AS `sum` FROM {$this->table}"
            . ($this->wheres ? ' WHERE ' . implode(' AND ', $this->wheres) : '');

        return (int)Model::db()->query($sql, $this->params)->first()->sum;
    }
}
