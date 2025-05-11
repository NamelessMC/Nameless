<?php

declare(strict_types=1);

namespace Database\ORM\Traits\Models;

use Model;

/**
 * Trait Serialization.
 *
 * Model conversion to array and JSON.
 *
 * @mixin Model
 */
trait Serialization
{
    /**
     * Return attributes + eager -loaded ligaments as an array.
     */
    public function toArray(): array
    {
        $result = $this->attributes;
        foreach ($this->relations as $key => $value) {
            $result[$key] = is_array($value)
                ? array_map(fn ($m) => $m->toArray(), $value)
                : ($value?->toArray());
        }

        return $result;
    }

    /**
     * Return the JSON-representation of the model.
     */
    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_UNESCAPED_UNICODE);
    }
}
