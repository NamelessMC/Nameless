<?php

/**
 * Centralizes read/write casts for Model attributes.
 */
class CastManager
{
    public static function castForRead(string $key, mixed $value, array $casts): mixed
    {
        $castType = $casts[$key] ?? null;

        if ($value === null || $castType === null) {
            return $value;
        }

        try {
            return match (true) {
                $castType === 'int' => (int)$value,
                $castType === 'float' => (float)$value,
                $castType === 'bool' => (bool)$value,
                $castType === 'decimal' => (string)number_format((float)$value, 2, '.', ''),
                $castType === 'uppercase' => strtoupper((string)$value),
                $castType === 'lowercase' => strtolower((string)$value),

                $castType === 'datetime' => new \DateTime((string)$value),
                $castType === 'date' => (new \DateTime((string)$value))->format('Y-m-d'),
                $castType === 'timestamp' => (new \DateTime())->setTimestamp((int)$value),

                in_array($castType, ['array', 'json'], true)
                => json_decode((string)$value, true) ?: [],

                $castType === 'object'
                => json_decode((string)$value),

                // enum (UnitEnum or BackedEnum)
                is_subclass_of($castType, UnitEnum::class)
                => call_user_func([$castType, 'from'], $value),

                // custom castable
                is_subclass_of($castType, Castable::class)
                => (new $castType)->cast($value),

                default => $value,
            };
        } catch (\Throwable $e) {
            throw new RuntimeException(
                "Failed to cast attribute '{$key}' on read: " . $e->getMessage(),
                0,
                $e
            );
        }
    }

    public static function prepareForWrite(array $attrs, array $casts, string $primaryKey): array
    {
        $data = $attrs;

        foreach ($casts as $key => $castType) {
            if (!array_key_exists($key, $data) || $data[$key] === null) {
                continue;
            }

            $raw = $data[$key];

            switch (true) {
                case in_array($castType, ['array', 'json', 'object'], true):
                    if (is_array($raw) || is_object($raw)) {
                        $data[$key] = json_encode($raw);
                    }
                    break;

                case $castType === 'int':
                case $castType === 'float':
                case $castType === 'bool':
                    $data[$key] = match ($castType) {
                        'int' => (int)$raw,
                        'float' => (float)$raw,
                        'bool' => $raw ? 1 : 0,
                    };
                    break;

                case $castType === 'decimal':
                    $data[$key] = number_format((float)$raw, 2, '.', '');
                    break;

                case $castType === 'uppercase':
                    $data[$key] = strtoupper((string)$raw);
                    break;

                case $castType === 'lowercase':
                    $data[$key] = strtolower((string)$raw);
                    break;

                case $castType === 'datetime':
                    $data[$key] = self::formatDateTime($raw);
                    break;

                case $castType === 'date':
                    $data[$key] = self::formatDate($raw);
                    break;

                case $castType === 'timestamp':
                    $data[$key] = self::formatTimestamp($raw);
                    break;

                case is_subclass_of($castType, UnitEnum::class):
                    // safe check → only BackedEnum has value
                    $data[$key] = $raw instanceof \BackedEnum ? $raw->value : (string)$raw;
                    break;

                case is_subclass_of($castType, Castable::class):
                    $data[$key] = (new $castType)->cast($raw);
                    break;
            }
        }

        if (!isset($attrs[$primaryKey])) {
            unset($data[$primaryKey]);
        }

        return $data;
    }

    private static function formatDateTime(mixed $val): string
    {
        if ($val instanceof \DateTimeInterface) {
            return $val->format('Y-m-d H:i:s');
        }
        return date('Y-m-d H:i:s', strtotime((string)$val));
    }

    private static function formatDate(mixed $val): string
    {
        if ($val instanceof \DateTimeInterface) {
            return $val->format('Y-m-d');
        }
        return date('Y-m-d', strtotime((string)$val));
    }

    private static function formatTimestamp(mixed $val): int
    {
        if ($val instanceof \DateTimeInterface) {
            return $val->getTimestamp();
        }
        return (int)$val;
    }
}
