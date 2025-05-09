<?php
declare(strict_types=1);

namespace Database\ORM\Casting;

use Database\ORM\Casting\Contract\BuiltInCaster;
use Database\ORM\Casting\Contract\CustomCaster;
use Database\ORM\Casting\Implementations\JsonCaster;
use Database\ORM\Casting\Implementations\DateTimeCaster;

/**
 * Central manager for all attribute casts.
 */
class Manager
{
    /**
     * Map of built-in cast name → caster class.
     *
     * @var array<string,class-string<BuiltInCaster>>
     */
    protected static array $builtInMap = [
        'json' => JsonCaster::class,
        'array' => JsonCaster::class,
        'object' => JsonCaster::class,
        'datetime' => DateTimeCaster::class,
        'date' => DateTimeCaster::class,
        'timestamp' => DateTimeCaster::class,
    ];

    /**
     * Cast value when reading from DB.
     *
     * @param string $type
     * @param mixed $value
     * @return mixed
     */
    public static function read(string $type, mixed $value): mixed
    {
        if ($value === null) {
            return null;
        }

        // built-in caster?
        if (isset(self::$builtInMap[$type])) {
            /** @var BuiltInCaster $caster */
            $caster = new (self::$builtInMap[$type])();
            return $caster->read($value);
        }

        // custom caster?
        if (is_subclass_of($type, CustomCaster::class)) {
            /** @var CustomCaster $caster */
            $caster = new $type();
            return $caster->cast($value, false);
        }

        // primitive fallback
        return match ($type) {
            'int' => (int)$value,
            'float' => (float)$value,
            'bool' => (bool)$value,
            'decimal' => number_format((float)$value, 2, '.', ''),
            'uppercase' => strtoupper((string)$value),
            'lowercase' => strtolower((string)$value),
            default => $value,
        };
    }

    /**
     * Prepare value when writing to DB.
     *
     * @param string $type
     * @param mixed $value
     * @return mixed
     */
    public static function write(string $type, mixed $value): mixed
    {
        if ($value === null) {
            return null;
        }

        // built-in caster?
        if (isset(self::$builtInMap[$type])) {
            /** @var BuiltInCaster $caster */
            $caster = new (self::$builtInMap[$type])();
            return $caster->write($value);
        }

        // custom caster?
        if (is_subclass_of($type, CustomCaster::class)) {
            /** @var CustomCaster $caster */
            $caster = new $type();
            return $caster->cast($value, true);
        }

        // primitive fallback
        return match ($type) {
            'int' => (int)$value,
            'float' => (float)$value,
            'bool' => ($value ? 1 : 0),
            'decimal' => number_format((float)$value, 2, '.', ''),
            'uppercase' => strtoupper((string)$value),
            'lowercase' => strtolower((string)$value),
            default => $value,
        };
    }
}
