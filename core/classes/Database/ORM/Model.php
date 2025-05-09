<?php

use Database\ORM\Casting\Manager;
use Database\ORM\Traits\Models\Queryable;
use Database\ORM\Traits\Models\Relations;

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
    use Relations;
    use Queryable;

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

    /**
     * @param array|object $attrs
     */
    public function __construct(array|object $attrs = [])
    {
        $this->fill($attrs);
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
     * @param  string $key
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
        foreach ((array) $attrs as $k => $v) {
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
}
