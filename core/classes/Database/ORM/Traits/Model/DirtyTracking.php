<?php

declare(strict_types=1);

namespace Database\ORM\Traits\Models;

use Model;

/**
 * Trait DirtyTracking.
 *
 * Tracking changes in model attributes.
 *
 * @mixin Model
 */
trait DirtyTracking
{
    /** @var array<string,mixed> Original attributes after loading/saving */
    protected array $original = [];

    /**
     * Return the original attribute value.
     */
    public function getOriginal(string $key): mixed
    {
        return $this->original[$key] ?? null;
    }

    /**
     * Return all the changed attributes.
     *
     * @return array<string,mixed>
     */
    public function getChanges(): array
    {
        $changes = [];
        foreach ($this->attributes as $key => $value) {
            $orig = $this->original[$key] ?? null;
            if ($value !== $orig) {
                $changes[$key] = $value;
            }
        }
        return $changes;
    }

    /**
     * Is there changes in the entire model or one attribute?
     */
    public function isDirty(string $key = null): bool
    {
        if ($key !== null) {
            return $this->getOriginal($key) !== ($this->attributes[$key] ?? null);
        }
        return !empty($this->getChanges());
    }

    /**
     * Restarts database attributes.
     *
     * @return static|null
     */
    public function refresh(): mixed
    {
        return static::query()
            ->find($this->{static::primaryKey()})
            ?->fill($this->attributes);
    }

    /**
     * Closes the model without a primary key.
     *
     * @return static
     */
    public function replicate(): static
    {
        $copy = clone $this;
        unset($copy->attributes[static::primaryKey()]);
        return $copy;
    }

    /**
     * Set "original" attributes.
     *
     * @param array<string,mixed> $attrs
     */
    protected function syncOriginal(array $attrs): void
    {
        $this->original = $attrs;
    }
}
