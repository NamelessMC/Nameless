<?php
declare(strict_types=1);

namespace Casting\Implementations;

use Casting\Contract\BuiltInCaster;
use DateTime;
use DateTimeInterface;

/**
 * Caster for date/time columns.
 */
class DateTimeCaster implements BuiltInCaster
{
    public function read(mixed $value): mixed
    {
        try {
            return new DateTime((string)$value);
        } catch (\Exception $e) {
            throw new \RuntimeException(
                "Failed to cast attribute on read: " . $e->getMessage(),
                0,
                $e
            );
        }
    }

    public function write(mixed $value): mixed
    {
        if ($value instanceof DateTimeInterface) {
            return $value->format('Y-m-d H:i:s');
        }
        return date('Y-m-d H:i:s', strtotime((string)$value));
    }
}
