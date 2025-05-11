<?php

declare(strict_types=1);

namespace Database\ORM\Traits\Models;

use Database\ORM\Relation;
use Database\ORM\Support\Collection;
use Model;
use RuntimeException;

/**
 * @mixin Model
 * @property-read Collection|Model[] $relations
 * Defines methods hasMany(), belongsTo(), belongsToMany().
 */
trait Relations
{
    /** @var array<string,mixed> Eager-loaded relations */
    protected array $relations = [];

    /**
     * Called Query->eagerLoad()
     * @internal
     */
    public function setRelation(string $name, mixed $value): void
    {
        if (is_array($value)) {
            $value = new Collection($value);
        }
        $this->relations[$name] = $value;
    }

    private function relationLazyLoadException(string $key): RuntimeException
    {
        $rel = $this->{$key}();
        $hint = $rel->getType() === Relation::TYPE_HAS_MANY
            ? "Use ->with('{$key}')->get()"
            : "Use ->with('{$key}')->find(\$id)";
        return new RuntimeException(
            "Lazy loading of '{$key}' is disabled. {$hint}"
        );
    }

    public function belongsTo(string $modelClass, string $foreignKey): Relation
    {
        return Relation::belongsTo(
            $modelClass,
            $foreignKey,
            static::primaryKey()
        )->setParent($this);
    }

    public function belongsToMany(
        string  $modelClass,
        string  $pivotTable,
        string  $foreignPivotKey,
        string  $relatedPivotKey,
        ?string $parentKey = null,
        ?string $relatedKey = null
    ): Relation
    {
        $parentKey = $parentKey ?? static::primaryKey();
        $relatedKey = $relatedKey ?? $modelClass::primaryKey();
        return Relation::belongsToMany(
            $modelClass,
            $pivotTable,
            $foreignPivotKey,
            $relatedPivotKey,
            $parentKey,
            $relatedKey
        )->setParent($this);
    }

    public function hasMany(string $modelClass, string $foreignKey): Relation
    {
        return Relation::hasMany(
            $modelClass,
            $foreignKey,
            static::primaryKey()
        )->setParent($this);
    }
}
