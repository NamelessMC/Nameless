<?php

declare(strict_types=1);

namespace Database\ORM\Traits\Models;

use Database\ORM\Query;
use Database\ORM\Support\Collection;
use DB;
use Model;
use RuntimeException;

/**
 * @mixin Model
 */
trait Queryable
{
    /**
     * @return Collection
     */
    public static function all(): Collection
    {
        return static::query()->get();
    }

    /**
     * @param  string $col
     * @return float
     */
    public static function avg(string $col): float
    {
        return static::query()->avg($col);
    }

    /**
     * Chunk-processing: Loading packs by $size.
     */
    public static function chunk(int $size, callable $cb): void
    {
        static::query()->chunk($size, $cb);
    }

    /** @return int */
    public static function count(): int
    {
        return static::query()->count();
    }

    /**
     * @param  array  $attrs
     * @return static
     */
    public static function create(array $attrs): static
    {
        return static::query()->create($attrs);
    }

    /** @return DB */
    public static function db(): DB
    {
        return DB::getInstance();
    }

    /**
     * @param  string $col
     * @param  string $op
     * @param  mixed  $val
     * @return bool
     */
    public static function exists(string $col, string $op, mixed $val): bool
    {
        return static::where($col, $op, $val)->exists();
    }

    /**
     * @param  array  $attrs
     * @param  array  $values
     * @return static
     */
    public static function firstOrCreate(array $attrs, array $values = []): static
    {
        $instance = static::where(...)->first();

        return $instance ?? static::create(array_merge($attrs, $values));
    }

    /**
     * @param  int         $id
     * @return static|null
     */
    public static function find(int $id): ?static
    {
        return static::query()->find($id);
    }

    /**
     * @param  int    $id
     * @return static
     */
    public static function findOrFail(int $id): static
    {
        return static::find($id)
            ?? throw new RuntimeException("Model with ID {$id} not found.");
    }

    /**
     * @return Collection
     */
    public static function get(): Collection
    {
        return static::query()->get();
    }

    /**
     * @param  string $col
     * @return int
     */
    public static function max(string $col): int
    {
        return static::query()->max($col);
    }

    /**
     * @param  string $col
     * @return int
     */
    public static function min(string $col): int
    {
        return static::query()->min($col);
    }

    /** @return array<string,mixed> */
    public static function paginate(int $perPage, int $page = 1): array
    {
        return static::query()->paginate($perPage, $page);
    }

    /** @return array<int|string,mixed> */
    public static function pluck(string $col, ?string $keyCol = null): array
    {
        return static::query()->pluck($col, $keyCol);
    }

    /**
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
     * @param  string $col
     * @return int
     */
    public static function sum(string $col): int
    {
        return static::query()->sum($col);
    }

    /**
     * @param  array  $conds
     * @param  array  $values
     * @return static
     */
    public static function updateOrCreate(array $conds, array $values): static
    {
        $instance = static::where(...)->first();
        if ($instance) {
            $instance->fill($values)->save();

            return $instance;
        }

        return static::create(array_merge($conds, $values));
    }

    /** @return Query<static> */
    public static function when(bool $condition, callable $cb): Query
    {
        return $condition
            ? $cb(static::query())
            : static::query();
    }

    /** @return Query<static> */
    public static function where(string $col, mixed $opOrVal, mixed $val = null): Query
    {
        return static::query()->where($col, $opOrVal, $val);
    }

    /** @return Query<static> */
    public static function whereIn(string $col, array $vals): Query
    {
        return static::query()->whereIn($col, $vals);
    }

    /** @return Query<static> */
    public static function with(array|string $rels): Query
    {
        return static::query()->with($rels);
    }

    /** @return Query<static> */
    public static function tap(callable $cb): Query
    {
        $q = static::query();
        $cb($q);

        return $q;
    }
}
