<?php
declare(strict_types=1);

namespace Database\ORM\Traits\Models;

use DB;
use QueryBuilder;
use RuntimeException;

/**
 * Trait Queryable
 *
 * Provides all the static query methods:
 *  - db(), tableName()
 *  - query(), find(), findOrFail(), create(), all(), get()
 *  - where(), whereIn(), pluck(), with()
 */
trait Queryable
{
    /** @return DB */
    public static function db(): DB
    {
        return DB::getInstance();
    }

    protected static function tableName(): string
    {
        return '`' . static::$prefix . static::$table . '`';
    }

    /**
     * @return QueryBuilder<static>
     */
    public static function query(): QueryBuilder
    {
        return new QueryBuilder(
            static::tableName(),
            static::$primaryKey,
            static::class
        );
    }

    public static function find(int $id): ?static
    {
        return static::query()->find($id);
    }

    public static function findOrFail(int $id): static
    {
        return static::find($id)
            ?? throw new RuntimeException("Model with ID {$id} not found.");
    }

    public static function create(array $attrs): static
    {
        return static::query()->create($attrs);
    }

    /** @return static[] */
    public static function all(): array
    {
        return static::query()->get();
    }

    /** @return static[] */
    public static function get(): array
    {
        return static::query()->get();
    }

    /**
     * @return QueryBuilder<static>
     */
    public static function where(string $column, string $op, mixed $val): QueryBuilder
    {
        return static::query()->where($column, $op, $val);
    }

    /** @return QueryBuilder<static> */
    public static function whereIn(string $column, array $vals): QueryBuilder
    {
        return static::query()->whereIn($column, $vals);
    }

    /** @return array<int|string,mixed> */
    public static function pluck(string $col, ?string $keyCol = null): array
    {
        return static::query()->pluck($col, $keyCol);
    }

    /**
     * @return QueryBuilder<static>
     */
    public static function with(array|string $rels): QueryBuilder
    {
        return static::query()->with($rels);
    }
}
