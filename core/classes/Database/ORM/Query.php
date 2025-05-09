<?php

declare(strict_types=1);

namespace Database\ORM;

use Database\ORM\Traits\Query\Debuggable;
use Database\ORM\Traits\Query\EagerLoads;
use Model;

/**
 * Builds & executes queries, now with nested ->with() support.
 *
 * @template TModel of Model
 */
class Query
{
    use Debuggable;
    use EagerLoads;

    protected string $table;
    protected string $primaryKey;
    /** @var class-string<TModel> */
    protected string $modelClass;

    /**
     * relationName => [ nested, relations ]
     * e.g. [ 'statistics' => ['server'], 'foo' => [] ].
     * @var array<string,string[]>
     */
    protected array $with = [];

    protected array $wheres = [];
    protected array $params = [];
    protected ?string $orderBy = null;
    protected ?int $limit = null;

    /**
     * @param string $table
     * @param string $primaryKey
     * @param class-string<TModel> $modelClass
     */
    public function __construct(string $table, string $primaryKey, string $modelClass)
    {
        $this->table = $table;
        $this->primaryKey = $primaryKey;
        $this->modelClass = $modelClass;
    }

    /**
     * Accepts dot notation, e.g. 'statistics.server'.
     *
     * @param array|string $relations
     * @return $this
     */
    public function with(array|string $relations): static
    {
        foreach ((array)$relations as $r) {
            if (str_contains($r, '.')) {
                [$root, $child] = explode('.', $r, 2);
                $this->with[$root][] = $child;
            } else {
                $this->with[$r] = $this->with[$r] ?? [];
            }
        }

        return $this;
    }

    /**
     * Add a basic WHERE clause.
     *
     * @return $this
     */
    public function where(string $col, string $op, mixed $val): static
    {
        $this->wheres[] = "`{$col}` {$op} ?";
        $this->params[] = $val;

        return $this;
    }

    /**
     * Add a WHERE IN (...) clause.
     *
     * @return $this
     */
    public function whereIn(string $col, array $vals): static
    {
        $ph = implode(',', array_fill(0, count($vals), '?'));
        $this->wheres[] = "`{$col}` IN ({$ph})";
        $this->params = array_merge($this->params, $vals);

        return $this;
    }

    /**
     * Retrieve a single column’s values from the result set.
     *
     * @param string $column
     * @param string|null $keyColumn
     * @return array<int|string,mixed>
     */
    public function pluck(string $column, ?string $keyColumn = null): array
    {
        $cols = "`{$column}`" . ($keyColumn ? ", `{$keyColumn}`" : '');
        $sql = "SELECT {$cols} FROM {$this->table}";

        if ($this->wheres) {
            $sql .= ' WHERE ' . implode(' AND ', $this->wheres);
        }
        if ($this->orderBy) {
            $sql .= " {$this->orderBy}";
        }
        if ($this->limit !== null) {
            $sql .= " LIMIT {$this->limit}";
        }

        $rows = Model::db()
            ->query($sql, $this->params, true)
            ->results();

        $result = [];
        foreach ($rows as $row) {
            $val = $row->{$column};
            if ($keyColumn) {
                $result[$row->{$keyColumn}] = $val;
            } else {
                $result[] = $val;
            }
        }

        return $result;
    }

    /**
     * Set ORDER BY clause.
     *
     * @return $this
     */
    public function orderBy(string $col, string $dir = 'ASC'): static
    {
        $this->orderBy = "ORDER BY `{$col}` {$dir}";

        return $this;
    }

    /**
     * Set LIMIT clause.
     *
     * @return $this
     */
    public function limit(int $l): static
    {
        $this->limit = $l;

        return $this;
    }

    /**
     * @return TModel[]
     */
    public function get(): array
    {
        $rows = Model::db()
            ->query($this->buildSelect(), $this->params, true)
            ->results();

        $models = array_map(
            fn($r) => new $this->modelClass((array)$r),
            $rows
        );

        if ($this->with) {
            $models = $this->eagerLoad($models);
        }

        return $models;
    }

    /**
     * @return TModel|null
     */
    public function first(): mixed
    {
        return $this->limit(1)->get()[0] ?? null;
    }

    /**
     * @return TModel|null
     */
    public function find(int $id): mixed
    {
        return $this->where($this->primaryKey, '=', $id)->first();
    }

    /**
     * Insert a new record and return its Model.
     *
     * @return TModel
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
     * Update a record by primary key.
     */
    public function update(array $data, mixed $id): bool
    {
        $set = implode(',', array_map(fn($c) => "`{$c}` = ?", array_keys($data)));
        $sql = "UPDATE {$this->table} SET {$set} WHERE `{$this->primaryKey}` = ?";

        return !Model::db()->query($sql, [...array_values($data), $id])->error();
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
     * Increment a numeric column.
     */
    public function increment(string $col, int $amt, mixed $id): bool
    {
        return !Model::db()->query(
            "UPDATE {$this->table} 
             SET `{$col}` = `{$col}` + ? 
             WHERE `{$this->primaryKey}` = ?",
            [$amt, $id]
        )->error();
    }

    /**
     * Decrement a numeric column.
     */
    public function decrement(string $col, int $amt, mixed $id): bool
    {
        return $this->increment($col, -$amt, $id);
    }

    /**
     * Get the sum of a column.
     */
    public function sum(string $col): int
    {
        $sql = "SELECT SUM(`{$col}`) AS `sum` FROM {$this->table}";

        if ($this->wheres) {
            $sql .= ' WHERE ' . implode(' AND ', $this->wheres);
        }

        return (int)Model::db()->query($sql, $this->params)->first()->sum;
    }

    /**
     * Get the average of a column.
     */
    public function avg(string $col): float
    {
        $sql = "SELECT AVG(`{$col}`) AS `avg` FROM {$this->table}";

        if ($this->wheres) {
            $sql .= ' WHERE ' . implode(' AND ', $this->wheres);
        }

        return (float)Model::db()->query($sql, $this->params)->first()->avg;
    }

    /**
     * Get the maximum value of a column.
     */
    public function max(string $col): int
    {
        $sql = "SELECT MAX(`{$col}`) AS `max` FROM {$this->table}";

        if ($this->wheres) {
            $sql .= ' WHERE ' . implode(' AND ', $this->wheres);
        }

        return (int)Model::db()->query($sql, $this->params)->first()->max;
    }

    /**
     * Get the minimum value of a column.
     */
    public function min(string $col): int
    {
        $sql = "SELECT MIN(`{$col}`) AS `min` FROM {$this->table}";

        if ($this->wheres) {
            $sql .= ' WHERE ' . implode(' AND ', $this->wheres);
        }

        return (int)Model::db()->query($sql, $this->params)->first()->min;
    }

    /**
     * Get the count of rows in the table.
     *
     * @param string $col Column name to count, or '*' for all.
     */
    public function count(string $col = '*'): int
    {
        $column = $col === '*' ? '*' : "`{$col}`";

        $sql = "SELECT COUNT({$column}) AS `count` FROM {$this->table}";

        if ($this->wheres) {
            $sql .= ' WHERE ' . implode(' AND ', $this->wheres);
        }

        $row = Model::db()
            ->query($sql, $this->params)
            ->first();

        return (int)($row->count ?? 0);
    }


    /**
     * Compile the SELECT SQL.
     */
    protected function buildSelect(): string
    {
        $sql = "SELECT * FROM {$this->table}";
        if ($this->wheres) {
            $sql .= ' WHERE ' . implode(' AND ', $this->wheres);
        }
        if ($this->orderBy) {
            $sql .= " {$this->orderBy}";
        }
        if ($this->limit !== null) {
            $sql .= " LIMIT {$this->limit}";
        }

        return $sql;
    }
}
