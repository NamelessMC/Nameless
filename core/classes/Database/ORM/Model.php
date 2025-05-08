<?php

/**
 * Base Model class providing ActiveRecord-style API
 * with proper eager + lazy loading of relations.
 */
abstract class Model
{
    public static string $prefix       = 'nl2_';
    protected static string $table      = '';
    protected static string $primaryKey = 'id';
    protected static array  $casts      = [];

    /** Raw database attributes */
    protected array $attributes = [];

    /** Eager- or lazy-loaded relation data */
    private array $relationsData = [];

    /**
     * Get the DB singleton.
     */
    public static function db(): DB
    {
        return DB::getInstance();
    }

    /**
     * Optionally fill initial attributes.
     */
    public function __construct(array|object $attributes = [])
    {
        $this->fill($attributes);
    }

    /**
     * Start a new query for this model.
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
     * Find by primary key.
     */
    public static function find(int $id): ?static
    {
        return static::query()->find($id);
    }

    /**
     * Throw if not found.
     */
    public static function findOrFail(int $id): static
    {
        return static::find($id)
            ?? throw new RuntimeException("Model with ID {$id} not found.");
    }

    /**
     * Create & persist a new record.
     */
    public static function create(array $attributes): static
    {
        return static::query()->create($attributes);
    }

    /**
     * Fetch all records.
     *
     * @return static[]
     */
    public static function all(): array
    {
        return static::query()->get();
    }

    /**
     * Save or update this record.
     */
    public function save(): bool
    {
        $this->fireEvent('saving');

        if (!empty($this->{static::$primaryKey})) {
            $ok = static::query()
                ->update($this->attributes, $this->{static::$primaryKey});
            $this->fireEvent('saved');
            return $ok;
        }

        $new = static::create($this->attributes);
        $this->attributes = $new->attributes;
        $this->fireEvent('saved');
        return true;
    }

    /**
     * Delete this record.
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
     * 1) Return eager-loaded relation data from $relationsData
     * 2) Return a normal attribute value
     * 3) If accessing a relation method that was not eager-loaded via with(), throw an exception
     *
     * @param string $key
     * @return mixed
     * @throws RuntimeException if attempting to lazy-load a relation
     */
    public function __get(string $key): mixed
    {
        // 1) Already eager-loaded?
        if (array_key_exists($key, $this->relationsData)) {
            return $this->relationsData[$key];
        }

        // 2) Plain attribute?
        if (array_key_exists($key, $this->attributes)) {
            return $this->castAttribute($key, $this->attributes[$key]);
        }

        // 3) Relation method exists → error with example model call
        if (method_exists($this, $key)) {
            // Get the relation definition
            $rel = $this->{$key}();

            // Build the snippet they need
            $modelClass = static::class;
            if ($rel->type === 'hasMany') {
                $snippet = "{$modelClass}::query()->with('{$key}')->get();";
            } else { // belongsTo
                $snippet = "{$modelClass}::query()->with('{$key}')->find(\$id);";
            }

            throw new RuntimeException(
                "Lazy loading of relation '{$key}' is disabled.\n"
                . "Please eager-load it by calling your model, for example:\n\n"
                . "    {$snippet}\n"
            );
        }

        // 4) Nothing found
        return null;
    }



    /**
     * Magic setter: put everything into attributes.
     */
    public function __set(string $key, mixed $value): void
    {
        $this->attributes[$key] = $value;
    }

    /**
     * Fill attributes from array or object.
     */
    public function fill(array|object $attrs): static
    {
        foreach ((array)$attrs as $k => $v) {
            $this->attributes[$k] = $v;
        }
        return $this;
    }

    /**
     * Lifecycle event hook.
     */
    protected function fireEvent(string $event): void
    {
        if (method_exists($this, $event)) {
            $this->{$event}();
        }
    }

    /**
     * Cast raw DB values to PHP types.
     */
    protected function castAttribute(string $key, mixed $value): mixed
    {
        return match (static::$casts[$key] ?? null) {
            'int'      => (int)$value,
            'float'    => (float)$value,
            'bool'     => (bool)$value,
            'datetime' => new DateTime($value),
            default    => $value,
        };
    }

    /**
     * Helper for QueryBuilder eagerLoad:
     * explicitly inject into relationsData.
     */
    public function setRelation(string $name, mixed $value): void
    {
        $this->relationsData[$name] = $value;
    }

    /**
     * Build the full table name (with prefix/backticks).
     */
    protected static function tableName(): string
    {
        return '`' . static::$prefix . static::$table . '`';
    }

    /**
     * Define a hasMany relation.
     *
     * @param class-string<Model> $model
     * @param string              $foreignKey child.table FK column
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
     * Define a belongsTo relation.
     *
     * @param class-string<Model> $model
     * @param string              $foreignKey this.table FK column
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
