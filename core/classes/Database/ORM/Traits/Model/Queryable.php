<?php

declare(strict_types=1);

namespace Database\ORM\Traits\Models;

use Model;
use Database\ORM\Query;
use Database\ORM\Support\Collection;
use DB;
use RuntimeException;

/**
 * @mixin Model
 */
trait Queryable
{
    /**
     * Get all records.
     *
     * @return Collection
     */
    public static function all(): Collection
    {
        return static::query()->get();
    }

    /**
     * Compute the average of a column.
     *
     * @param string $col
     * @return float
     */
    public static function avg(string $col): float
    {
        return static::query()->avg($col);
    }

    /**
     * Chunk processing: load in batches of $size.
     *
     * @param int $size
     * @param callable $cb Receives a Collection<static>
     */
    public static function chunk(int $size, callable $cb): void
    {
        static::query()->chunk($size, $cb);
    }

    /**
     * Count rows.
     *
     * @return int
     */
    public static function count(): int
    {
        return static::query()->count();
    }

    /**
     * Create a new record.
     *
     * @param array<string,mixed> $attrs
     * @return static
     */
    public static function create(array $attrs): static
    {
        return static::query()->create($attrs);
    }

    /**
     * Get the DB instance.
     */
    public static function db(): DB
    {
        return DB::getInstance();
    }

    /**
     * Determine if any record exists matching the condition.
     *
     * @param string $col
     * @param string $op
     * @param mixed $val
     * @return bool
     */
    public static function exists(string $col, string $op, mixed $val): bool
    {
        return static::query()->where($col, $op, $val)->exists();
    }

    /**
     * Find the first record matching the attributes, or create it.
     *
     * @param array<string,mixed> $attrs
     * @param array<string,mixed> $values
     * @return static
     */
    public static function firstOrCreate(array $attrs, array $values = []): static
    {
        $query = static::query();
        foreach ($attrs as $key => $value) {
            $query->where($key, '=', $value);
        }
        $instance = $query->first();
        return $instance ? $instance : static::query()->create(array_merge($attrs, $values));
    }

    /**
     * Find a record by primary key.
     *
     * @param int $id
     * @return Model|null
     */
    public static function find(int $id): Model|null
    {
        return static::query()->find($id);
    }

    /**
     * Find a record by primary key or throw.
     *
     * @param int $id
     * @return Model
     */
    public static function findOrFail(int $id): Model
    {
        return static::find($id)
            ?? throw new RuntimeException("Model with ID {$id} not found.");
    }

    /**
     * Get records (alias of all()).
     *
     * @return Collection
     */
    public static function get(): Collection
    {
        return static::query()->get();
    }

    /**
     * Get the maximum value of a column.
     *
     * @param string $col
     * @return int
     */
    public static function max(string $col): int
    {
        return static::query()->max($col);
    }

    /**
     * Get the minimum value of a column.
     *
     * @param string $col
     * @return int
     */
    public static function min(string $col): int
    {
        return static::query()->min($col);
    }

    /**
     * Paginate the query.
     *
     * @param int $perPage
     * @param int $page Defaults to 1
     * @return array{data:Collection&iterable<Model>}
     */
    public static function paginate(int $perPage, int $page = 1): array
    {
        return static::query()->paginate($perPage, $page);
    }

    /**
     * Pluck a single column’s values.
     *
     * @param string $col
     * @param string|null $keyCol
     * @return array<int|string,mixed>
     */
    public static function pluck(string $col, ?string $keyCol = null): array
    {
        return static::query()->pluck($col, $keyCol);
    }

    /**
     * Start a query.
     *
     * @return Query<static>
     */
    public static function query(): Query
    {
        return new Query(
            static::table(),
            static::primaryKey(),
            static::class
        );
    }

    /**
     * Get the sum of a column.
     *
     * @param string $col
     * @return int
     */
    public static function sum(string $col): int
    {
        return static::query()->sum($col);
    }

    /**
     * Update an existing record matching $conds or create it.
     *
     * @param array<string,mixed> $conds
     * @param array<string,mixed> $values
     * @return Model
     */
    public static function updateOrCreate(array $conds, array $values): Model
    {
        $instance = static::where(key($conds), '=', current($conds))->first();
        if ($instance) {
            $instance->fill($values)->save();
            return $instance;
        }
        return static::create(array_merge($conds, $values));
    }

    /**
     * Conditionally apply a callback to the query.
     *
     * @param bool $condition
     * @param callable $cb Receives Query<static>
     * @return Query<static>
     */
    public static function when(bool $condition, callable $cb): Query
    {
        $q = static::query();
        return $condition ? $cb($q) : $q;
    }

    /**
     * Add a basic WHERE clause.
     *
     * @param string $col
     * @param mixed $opOrVal Operator or value
     * @param mixed|null $val Value if 3 args
     * @return Query<static>
     */
    public static function where(string $col, mixed $opOrVal, mixed $val = null): Query
    {
        return static::query()->where($col, $opOrVal, $val);
    }

    /**
     * Add a WHERE IN (...) clause.
     *
     * @param string $col
     * @param array $vals
     * @return Query<static>
     */
    public static function whereIn(string $col, array $vals): Query
    {
        return static::query()->whereIn($col, $vals);
    }

    /**
     * Eager-load relations.
     *
     * @param string|string[] $rels
     * @return Query<static>
     */
    public static function with(array|string $rels): Query
    {
        return static::query()->with($rels);
    }

    /**
     * Tap into the query for debugging or logging.
     *
     * @param callable $cb Receives Query<static>
     * @return Query<static>
     */
    public static function tap(callable $cb): Query
    {
        $q = static::query();
        $cb($q);
        return $q;
    }
}
