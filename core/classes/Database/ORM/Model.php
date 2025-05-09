<?php

use Casting\Manager;

/**
 * Base ActiveRecord-style Model.
 *
 * Provides:
 *  - automatic table name resolution with prefix
 *  - basic CRUD: find, create, update, delete
 *  - attribute casting via CastManager
 *  - eager-only relation loading with hasMany/belongsTo
 */
abstract class Model
{
    /** @var string Table name prefix (e.g. 'nl2_'). */
    public static string $prefix = 'nl2_';

    /** @var string Table name without prefix/backticks. */
    protected static string $table = '';

    /** @var string Primary key column name. */
    protected static string $primaryKey = 'id';

    /**
     * @var array<string,string|class-string>
     *                                        Attribute casts, e.g. ['status'=>'bool','payload'=>JsonCaster::class].
     */
    protected static array $casts = [];

    /** @var array<string,mixed> Raw DB attributes. */
    protected array $attributes = [];

    /** @var array<string,mixed> Eager‐loaded relations. */
    private array $relations = [];

    /** @return DB */
    public static function db(): DB
    {
        return DB::getInstance();
    }

    /**
     * @param array|object $attrs
     */
    public function __construct(array|object $attrs = [])
    {
        $this->fill($attrs);
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

    /**
     * @param int $id
     * @return static|null
     */
    public static function find(int $id): ?static
    {
        return static::query()->find($id);
    }

    /**
     * @param int $id
     * @return static
     */
    public static function findOrFail(int $id): static
    {
        return static::find($id)
            ?? throw new RuntimeException("Model with ID {$id} not found.");
    }

    /**
     * @param array $attrs
     * @return static
     */
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

    /** @return bool */
    public function save(): bool
    {
        $this->fireEvent('saving');

        $pk = static::$primaryKey;
        $data = [];

        foreach ($this->attributes as $key => $value) {
            if (array_key_exists($key, static::$casts)) {
                $type = static::$casts[$key];
                $data[$key] = Manager::write($type, $value);
            } else {
                $data[$key] = $value;
            }
        }

        // We delete the primary key if we create a new entry
        if (!isset($this->attributes[$pk])) {
            unset($data[$pk]);
        }

        if (isset($this->attributes[$pk])) {
            $ok = static::query()->update($data, $this->attributes[$pk]);
        } else {
            $new = static::query()->create($data);
            $this->attributes = $new->attributes;
            $ok = true;
        }

        $this->fireEvent('saved');

        return $ok;
    }

    /** @return bool */
    public function delete(): bool
    {
        $this->fireEvent('deleting');
        $ok = static::query()->delete($this->{static::$primaryKey});
        $this->fireEvent('deleted');

        return $ok;
    }

    /**
     * Magic getter:
     * 1) returns eager‐loaded relation
     * 2) returns casted attribute
     * 3) throws if relation not eager‐loaded
     *
     * @param string $key
     * @return mixed
     */
    public function __get(string $key): mixed
    {
        if (array_key_exists($key, $this->relations)) {
            return $this->relations[$key];
        }
        if (array_key_exists($key, $this->attributes)) {
            $value = $this->attributes[$key];

            if (array_key_exists($key, static::$casts)) {
                $type = static::$casts[$key];
                return Manager::read($type, $value);
            }

            return $value;
        }

        if (method_exists($this, $key)) {
            throw $this->relationLazyLoadException($key);
        }

        return null;
    }

    /** Magic setter for attributes. */
    public function __set(string $key, mixed $value): void
    {
        $this->attributes[$key] = $value;
    }

    /** @return $this */
    public function fill(array|object $attrs): static
    {
        foreach ((array)$attrs as $k => $v) {
            $this->attributes[$k] = $v;
        }

        return $this;
    }

    protected function fireEvent(string $e): void
    {
        if (method_exists($this, $e)) {
            $this->{$e}();
        }
    }

    private function relationLazyLoadException(string $key): RuntimeException
    {
        $rel = $this->{$key}();
        $snippet = $rel->type === 'hasMany'
            ? static::class . "::query()->with('{$key}')->get();"
            : static::class . "::query()->with('{$key}')->find(\$id);";

        return new RuntimeException(
            "Lazy loading of relation '{$key}' is disabled.\n" .
            "Please eager‐load via:\n    {$snippet}\n"
        );
    }

    /** @internal Used by QueryBuilder */
    public function setRelation(string $name, mixed $value): void
    {
        $this->relations[$name] = $value;
    }

    protected static function tableName(): string
    {
        return '`' . static::$prefix . static::$table . '`';
    }

    /** Define one‐to‐many */
    public function hasMany(string $model, string $fk): Relation
    {
        return new Relation('hasMany', $model, $fk, static::$primaryKey);
    }

    /** Define inverse many‐to‐one */
    public function belongsTo(string $model, string $fk): Relation
    {
        return new Relation('belongsTo', $model, static::$primaryKey, $fk);
    }

    /**
     * Define a many-to-many relationship via a pivot table.
     *
     * @param string $model Fully-qualified related model class
     * @param string $pivotTable Pivot table name (without prefix)
     * @param string $foreignPivotKey Column in pivot table that refers to this model
     * @param string $relatedPivotKey Column in pivot table that refers to the related model
     * @param string|null $parentKey Primary key in this model's table (defaults to static::$primaryKey)
     * @param string|null $relatedKey Primary key in related model's table (defaults to RelatedModel::$primaryKey)
     * @return Relation
     */
    public function belongsToMany(
        string  $model,
        string  $pivotTable,
        string  $foreignPivotKey,
        string  $relatedPivotKey,
        ?string $parentKey = null,
        ?string $relatedKey = null
    ): Relation
    {
        // Use default keys if none provided
        $parentKey = $parentKey ?? static::$primaryKey;
        $relatedKey = $relatedKey ?? $model::$primaryKey;

        return new Relation('belongsToMany', $model, $pivotTable, $foreignPivotKey, $relatedPivotKey, $parentKey, $relatedKey);
    }
}
