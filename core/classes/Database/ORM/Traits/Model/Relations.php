<?php
declare(strict_types=1);

namespace Database\ORM\Traits\Models;

use Database\ORM\Relation;
use Model;
use RuntimeException;

/**
 * Trait Relations.
 *
 * Defines hasMany(), belongsTo(), belongsToMany() by calling Relation’s
 * named constructors, then binding $this as parent.
 *
 * Note: pivot table names and model table names must include any needed prefix.
 */
trait Relations
{
    /** @var array<string,mixed> Eagerly loaded relations */
    protected array $relations = [];

    /** @internal Called by the query builder for eager-loading */
    public function setRelation(string $name, mixed $value): void
    {
        $this->relations[$name] = $value;
    }

    private function relationLazyLoadException(string $key): RuntimeException
    {
        $rel = $this->{$key}();
        $hint = $rel->getType() === Relation::TYPE_HAS_MANY
            ? "e.g. " . static::class . "::query()->with('{$key}')->get();"
            : "e.g. " . static::class . "::query()->with('{$key}')->find(\$id);";

        return new RuntimeException(
            "Lazy loading of relation '{$key}' is disabled.\n" . $hint
        );
    }

    /**
     * One-to-many.
     *
     * @param class-string<Model> $modelClass Related model class
     * @param string $foreignKey Column on related table
     */
    public function hasMany(string $modelClass, string $foreignKey): Relation
    {
        return Relation::hasMany(
            $modelClass,
            $foreignKey,
            static::$primaryKey
        )->setParent($this);
    }

    /**
     * Many-to-one (inverse).
     *
     * @param class-string<Model> $modelClass Related model class
     * @param string $foreignKey Column on this table
     */
    public function belongsTo(string $modelClass, string $foreignKey): Relation
    {
        return Relation::belongsTo(
            $modelClass,
            $foreignKey,
            static::$primaryKey
        )->setParent($this);
    }

    /**
     * Many-to-many via pivot.
     *
     * @param class-string<Model> $modelClass Related model class
     * @param string $pivotTable **Full** pivot table name (including prefix)
     * @param string $foreignPivotKey Column on pivot for this model
     * @param string $relatedPivotKey Column on pivot for related model
     * @param string|null $parentKey Local PK column (defaults to static::$primaryKey)
     * @param string|null $relatedKey Related PK column (defaults to $modelClass::$primaryKey)
     */
    public function belongsToMany(
        string  $modelClass,
        string  $pivotTable,
        string  $foreignPivotKey,
        string  $relatedPivotKey,
        ?string $parentKey = null,
        ?string $relatedKey = null
    ): Relation
    {
        $parentKey = $parentKey ?? static::$primaryKey;
        $relatedKey = $relatedKey ?? $modelClass::$primaryKey;

        return Relation::belongsToMany(
            $modelClass,
            $pivotTable,
            $foreignPivotKey,
            $relatedPivotKey,
            $parentKey,
            $relatedKey
        )->setParent($this);
    }
}
