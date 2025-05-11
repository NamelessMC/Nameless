<?php

declare(strict_types=1);

use Database\ORM\Casting\Caster;
use Database\ORM\Traits\Models\Crud;
use Database\ORM\Traits\Models\DirtyTracking;
use Database\ORM\Traits\Models\Relations;
use Database\ORM\Traits\Models\Queryable;
use Database\ORM\Traits\Models\Serialization;
/**
 * Basic model
 *
 * @property-read \Database\ORM\Support\Collection|Model[] $relations
 */
abstract class Model
{
    use Crud;
    use DirtyTracking;
    use Relations;
    use Queryable;
    use Serialization;

    /** @var string Table name. */
    protected static string $table = '';

    /** @var string Primary key name. */
    protected static string $primaryKey = 'id';

    /**
     * @var array<string,string|class-string>
     *   Matching attributes to the types.
     */
    protected static array $casts = [];

    /** @var array<string,mixed> Raw materials attributes from the database. */
    protected array $attributes = [];

    /**
     * @param array|object $attrs
     */
    public function __construct(array|object $attrs = [])
    {
        $this->fill($attrs);
    }

    /**
     * @return string The name of the table for queries.
     */
    public static function table(): string
    {
        return static::$table;
    }

    /**
     * @return string Primary key name.
     */
    public static function primaryKey(): string
    {
        return static::$primaryKey;
    }

    /**
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
                return Caster::read($type, $value);
            }
            return $value;
        }
        if (method_exists($this, $key)) {
            throw $this->relationLazyLoadException($key);
        }
        return null;
    }

    /**
     * Magically sets the value of the attribute.
     *
     * @param string $key
     * @param mixed $value
     */
    public function __set(string $key, mixed $value): void
    {
        $this->attributes[$key] = $value;
    }

    /**
     * Massively fills the attributes from an array or object.
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
     * If the event method exists, it causes it.
     *
     * @param string $event
     */
    protected function fireEvent(string $event): void
    {
        if (method_exists($this, $event)) {
            $this->{$event}();
        }
    }
}
