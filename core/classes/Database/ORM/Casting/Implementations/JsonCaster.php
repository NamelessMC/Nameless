<?php
declare(strict_types=1);

namespace Casting\Implementations;

use Casting\Contract\BuiltInCaster;

/**
 * Caster for JSON-encoded columns.
 */
class JsonCaster implements BuiltInCaster
{
    public function read(mixed $value): mixed
    {
        $decoded = json_decode((string)$value, true);
        return $decoded !== null ? $decoded : [];
    }

    public function write(mixed $value): mixed
    {
        return json_encode($value);
    }
}
