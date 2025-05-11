<?php

declare(strict_types=1);

namespace Database\ORM\Traits\Models;

use Database\ORM\Casting\Caster;
use Model;

/**
 * @mixin Model
 */
trait Crud
{
    /**
     * Removes the recording from the database.
     *
     * @return bool
     */
    public function delete(): bool
    {
        $this->fireEvent('deleting');
        $ok = static::query()->delete($this->{static::primaryKey()});
        $this->fireEvent('deleted');

        return $ok;
    }

    /**
     * Stores (creates or updates) recording.
     *
     * @return bool
     */
    public function save(): bool
    {
        $this->fireEvent('saving');
        $pk = static::primaryKey();
        $data = [];
        foreach ($this->attributes as $key => $value) {
            if (array_key_exists($key, static::$casts)) {
                $type = static::$casts[$key];
                $data[$key] = Caster::write($type, $value);
            } else {
                $data[$key] = $value;
            }
        }
        if (!isset($this->attributes[$pk])) {
            unset($data[$pk]);
            $new = static::query()->create($data);
            $this->attributes = $new->attributes;
            $ok = true;
        } else {
            $ok = static::query()->update($data, $this->attributes[$pk]);
        }
        $this->fireEvent('saved');

        return $ok;
    }
}
