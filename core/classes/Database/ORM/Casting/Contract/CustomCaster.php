<?php
declare(strict_types=1);

namespace Casting\Contract;

/**
 * Marker interface for your own domain casters.
 * Single method handles both read & write directions.
 */
interface CustomCaster
{
    /**
     * Transform the value.
     *
     * @param mixed $value
     * @param bool  $forWrite  // false = reading from DB, true = writing
     * @return mixed
     */
    public function cast(mixed $value, bool $forWrite = false): mixed;
}
