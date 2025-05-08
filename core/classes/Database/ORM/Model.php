<?php

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
    /**
     * Table name prefix (e.g. 'nl2_').
     * @var string
     */
    public static string $prefix = 'nl2_';

    /**
     * Table name without prefix or backticks.
     * Each subclass must override.
     * @var string
     */
    protected static string $table = '';

    /**
     * Primary key column name. Default 'id'.
     * @var string
     */
    protected static string $primaryKey = 'id';

    /**
     * Attribute cast definitions.
     * Key = attribute name, value = cast type or class.
     * e.g. ['status' => 'bool', 'payload' => JsonCaster::class]
     * @var array
     */
    protected static array $casts = [];

    /**
     * Raw attributes loaded from the database.
     * @var array<string,mixed>
     */
    protected array $attributes = [];

    /**
     * Eager-loaded relation data.
     * Populated by QueryBuilder->eagerLoad().
     * @var array<string,mixed>
     */
    private array $relations = [];

    /**
     * Get the global DB instance.
     *
     * @return DB
     */
    public static function db(): DB
    {
        return DB::getInstance();
    }

    /**
     * Optionally initialize model with attributes.
     *
     * @param array|object $attrs  Raw DB row or attribute array
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
     * Find a record by primary key.
     *
     * @param int $id
     * @return static|null
     */
    public static function find(int $id): ?static
    {
        return static::query()->find($id);
    }

    /**
     * Find a record or throw if not found.
     *
     * @param int $id
     * @return static
     * @throws RuntimeException
     */
    public static function findOrFail(int $id): static
    {
        return static::find($id)
            ?? throw new RuntimeException("Model with ID {$id} not found.");
    }

    /**
     * Create & insert a new record.
     *
     * @param array<string,mixed> $attrs
     * @return static
     */
    public static function create(array $attrs): static
    {
        return static::query()->create($attrs);
    }

    /**
     * Retrieve all records.
     *
     * @return static[]
     */
    public static function all(): array
    {
        return static::query()->get();
    }

    /**
     * Insert or update this model.
     * Uses CastManager to prepare attributes for persistence.
     *
     * @return bool  True on success.
     */
    public function save(): bool
    {
        $this->fireEvent('saving');

        $pk   = static::$primaryKey;
        $data = CastManager::prepareForWrite(
            $this->attributes,
            static::$casts,
            $pk
        );

        if (isset($this->attributes[$pk])) {
            // existing record → update
            $ok = static::query()->update($data, $this->attributes[$pk]);
        } else {
            // new record → insert
            $new = static::query()->create($data);
            // sync newly generated attributes (e.g. primary key)
            $this->attributes = $new->attributes;
            $ok = true;
        }

        $this->fireEvent('saved');
        return $ok;
    }

    /**
     * Delete this record from the database.
     *
     * @return bool
     */
    public function delete(): bool
    {
        $this->fireEvent('deleting');
        $ok = static::query()->delete($this->{static::$primaryKey});
        $this->fireEvent('deleted');
        return $ok;
    }

    /**
     * Magic getter:
     * 1) returns eager-loaded relation if present
     * 2) returns casted attribute if present
     * 3) throws if attempting lazy loading of a relation
     *
     * @param string $key
     * @return mixed
     * @throws RuntimeException
     */
    public function __get(string $key): mixed
    {
        // 1) eager-loaded relation
        if (array_key_exists($key, $this->relations)) {
            return $this->relations[$key];
        }

        // 2) plain attribute → cast on read
        if (array_key_exists($key, $this->attributes)) {
            return CastManager::castForRead(
                $key,
                $this->attributes[$key],
                static::$casts
            );
        }

        // 3) method exists → relation defined but not eager-loaded
        if (method_exists($this, $key)) {
            throw $this->relationLazyLoadException($key);
        }

        // 4) nothing found
        return null;
    }

    /**
     * Magic setter: always writes into the raw attributes array.
     *
     * @param string $key
     * @param mixed  $value
     */
    public function __set(string $key, mixed $value): void
    {
        $this->attributes[$key] = $value;
    }

    /**
     * Bulk-fill attributes from an array or object.
     *
     * @param array|object $attrs
     * @return $this
     */
    public function fill(array|object $attrs): static
    {
        foreach ((array)$attrs as $k => $v) {
            $this->attributes[$k] = $v;
        }
        return $this;
    }

    /**
     * Trigger a lifecycle event method if it exists.
     *
     * @param string $event  e.g. 'saving', 'saved', 'deleting', 'deleted'
     */
    protected function fireEvent(string $event): void
    {
        if (method_exists($this, $event)) {
            $this->{$event}();
        }
    }

    /**
     * Build an exception when attempting to lazy-load a relation.
     *
     * @param string $key  Relation method name
     * @return RuntimeException
     */
    private function relationLazyLoadException(string $key): RuntimeException
    {
        $rel = $this->{$key}();
        $snippet = $rel->type === 'hasMany'
            ? static::class . "::query()->with('{$key}')->get();"
            : static::class . "::query()->with('{$key}')->find(\$id);";

        return new RuntimeException(
            "Lazy loading of relation '{$key}' is disabled.\n" .
            "Please eager-load via:\n    {$snippet}\n"
        );
    }

    /**
     * Used by QueryBuilder to inject eager-loaded data.
     *
     * @param string $name   Relation name
     * @param mixed  $value  Loaded relation data
     */
    public function setRelation(string $name, mixed $value): void
    {
        $this->relations[$name] = $value;
    }

    /**
     * Resolve the full table name including prefix and backticks.
     *
     * @return string
     */
    protected static function tableName(): string
    {
        return '`' . static::$prefix . static::$table . '`';
    }

    /**
     * Define a one-to-many (hasMany) relation.
     *
     * @param class-string<Model> $model      Related model class
     * @param string              $foreignKey FK column in the related table
     * @return Relation
     */
    public function hasMany(string $model, string $foreignKey): Relation
    {
        return new Relation(
            'hasMany',
            $model,
            $foreignKey,
            static::$primaryKey
        );
    }

    /**
     * Define an inverse one-to-many (belongsTo) relation.
     *
     * @param class-string<Model> $model      Parent model class
     * @param string              $foreignKey FK column in this table
     * @return Relation
     */
    public function belongsTo(string $model, string $foreignKey): Relation
    {
        return new Relation(
            'belongsTo',
            $model,
            static::$primaryKey,
            $foreignKey
        );
    }
}
