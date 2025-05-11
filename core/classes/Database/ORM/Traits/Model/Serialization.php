<?php
declare(strict_types=1);

namespace Database\ORM\Traits\Models;

use Database\ORM\Support\Collection;
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
     * Convert this model’s attributes and eager-loaded relations to an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $result = $this->attributes;
        foreach ($this->relations as $key => $relation) {
            $result[$key] = $relation->toArray();
        }
        return $result;
    }

    /**
     * Convert this model to its JSON representation.
     *
     * @return string
     */
    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_UNESCAPED_UNICODE);
    }
}
