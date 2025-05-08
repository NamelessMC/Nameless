<?php

abstract class Model
{
    public static string $prefix = 'nl2_';
    protected static string $table;
    protected static string $primaryKey = 'id';
    protected static array $casts = [];

    protected array $attributes = [];

    public static function db(): DB
    {
        return DB::getInstance();
    }

    public function __construct(array|object $attributes = [])
    {
        $this->fill($attributes);
    }

    public static function query(): QueryBuilder
    {
        return new QueryBuilder(static::tableName(), static::$primaryKey, static::class);
    }

    public static function find(int $id): ?static
    {
        return static::query()->find($id);
    }

    public static function findOrFail(int $id): static
    {
        return static::find($id) ?? throw new RuntimeException("Model with ID {$id} not found.");
    }

    public static function create(array $attributes): static
    {
        return static::query()->create($attributes);
    }

    public static function all(): array
    {
        return static::query()->get();
    }

    public function save(): bool
    {
        $this->fireEvent('saving');

        if (!empty($this->{static::$primaryKey})) {
            $result = static::query()->update($this->attributes, $this->{static::$primaryKey});
            $this->fireEvent('saved');
            return $result;
        }

        $model = static::create($this->attributes);
        $this->attributes = $model->attributes;
        $this->fireEvent('saved');
        return true;
    }

    public function delete(): bool
    {
        $this->fireEvent('deleting');
        $result = static::query()->delete($this->{static::$primaryKey});
        $this->fireEvent('deleted');
        return $result;
    }

    public function increment(string $column, int $amount = 1): bool
    {
        return static::query()->increment($column, $amount, $this->{static::$primaryKey});
    }

    public function decrement(string $column, int $amount = 1): bool
    {
        return $this->increment($column, -$amount);
    }

    public function toArray(): array
    {
        $array = [];

        foreach ($this->attributes as $key => $value) {
            $array[$key] = $this->castAttribute($key, $value);
        }

        foreach (get_object_vars($this) as $key => $value) {
            if ($value instanceof Model) {
                $array[$key] = $value->toArray();
            } elseif (is_array($value) && isset($value[0]) && $value[0] instanceof Model) {
                $array[$key] = array_map(fn($item) => $item->toArray(), $value);
            }
        }

        return $array;
    }

    public function __get($key): mixed
    {
        return $this->attributes[$key] ?? null;
    }

    public function __set($key, $value): void
    {
        $this->attributes[$key] = $value;
    }

    public function fill(array|object $attributes): static
    {
        foreach ((array)$attributes as $key => $value) {
            $this->attributes[$key] = $value;
        }
        return $this;
    }

    protected function fireEvent(string $event): void
    {
        if (method_exists($this, $event)) {
            $this->{$event}();
        }
    }

    protected function castAttribute(string $key, mixed $value): mixed
    {
        try {
            return match (static::$casts[$key] ?? null) {
                'int' => (int)$value,
                'float' => (float)$value,
                'bool' => (bool)$value,
                'datetime' => new DateTime($value),
                default => $value
            };
        } catch (Exception $e) {
            throw new RuntimeException($e->getMessage());
        }
    }

    protected static function tableName(): string
    {
        return '`' . static::$prefix . static::$table . '`';
    }
}






