<?php
declare(strict_types=1);

namespace Casting\Contract;

/**
 * Contract for framework-provided casters:
 * must support both read() and write() directions.
 */
interface BuiltInCaster
{
    /**
     * Cast value when reading from the database.
     *
     * @param mixed $value
     * @return mixed
     */
    public function read(mixed $value): mixed;

    /**
     * Prepare value when writing to the database.
     *
     * @param mixed $value
     * @return mixed
     */
    public function write(mixed $value): mixed;
}
