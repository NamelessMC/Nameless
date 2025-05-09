<?php
declare(strict_types=1);

namespace Database\ORM\Traits\Models;

use Database\ORM\Relation;
use RuntimeException;

/**
 * Trait Relations
 *
 * Provides:
 *  - setRelation() for QueryBuilder
 *  - relationLazyLoadException()
 *  - hasMany(), belongsTo(), belongsToMany()
 */
trait Relations
{
    /** @internal Used by QueryBuilder */
    public function setRelation(string $name, mixed $value): void
    {
        $this->relations[$name] = $value;
    }

    private function relationLazyLoadException(string $key): RuntimeException
    {
        $rel = $this->{$key}();
        $snippet = $rel->type === 'hasMany'
            ? static::class . "::query()->with('{$key}')->get();"
            : static::class . "::query()->with('{$key}')->find(\$id);";

        return new RuntimeException(
            "Lazy loading of relation '{$key}' is disabled.\n" .
            "Please eager‐load via:\n    {$snippet}\n"
        );
    }

    /** Define one‐to‐many */
    public function hasMany(string $model, string $fk): Relation
    {
        return new Relation('hasMany', $model, $fk, static::$primaryKey);
    }

    /** Define inverse many‐to‐one */
    public function belongsTo(string $model, string $fk): Relation
    {
        return new Relation('belongsTo', $model, static::$primaryKey, $fk);
    }

    /**
     * Define a many-to-many relationship via a pivot table.
     *
     * @param string $model
     * @param string $pivotTable
     * @param string $foreignPivotKey
     * @param string $relatedPivotKey
     * @param string|null $parentKey
     * @param string|null $relatedKey
     * @return Relation
     */
    public function belongsToMany(
        string  $model,
        string  $pivotTable,
        string  $foreignPivotKey,
        string  $relatedPivotKey,
        ?string $parentKey = null,
        ?string $relatedKey = null
    ): Relation
    {
        $parentKey = $parentKey ?? static::$primaryKey;
        $relatedKey = $relatedKey ?? $model::$primaryKey;

        return new Relation(
            'belongsToMany',
            $model,
            $pivotTable,
            $foreignPivotKey,
            $relatedPivotKey,
            $parentKey,
            $relatedKey
        );
    }
}
