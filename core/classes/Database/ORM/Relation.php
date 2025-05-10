<?php

declare(strict_types=1);

namespace Database\ORM;

use Model;
use RuntimeException;

/**
 * Encapsulates relationship metadata between two models.
 */
class Relation
{
    public const TYPE_HAS_MANY = 'hasMany';
    public const TYPE_BELONGS_TO = 'belongsTo';
    public const TYPE_BELONGS_TO_MANY = 'belongsToMany';

    protected string $type;
    protected string $modelClass;
    protected Model $parent;

    // for hasMany / belongsTo
    protected ?string $foreignKey = null;
    protected ?string $localKey = null;

    // for belongsToMany
    protected ?string $pivotTable = null;
    protected ?string $foreignPivotKey = null;
    protected ?string $relatedPivotKey = null;
    protected ?string $parentKey = null;
    protected ?string $relatedKey = null;

    /** Prevent direct instantiation; use named constructors below */
    private function __construct()
    {
    }

    /**
     * One-to-many relationship.
     *
     * @param class-string<Model> $modelClass Related model
     * @param string              $foreignKey FK column on related table
     * @param string              $localKey   PK column on this model
     */
    public static function hasMany(string $modelClass, string $foreignKey, string $localKey): self
    {
        $rel = new self();
        $rel->type = self::TYPE_HAS_MANY;
        $rel->modelClass = $modelClass;
        $rel->foreignKey = $foreignKey;
        $rel->localKey = $localKey;

        return $rel;
    }

    /**
     * Inverse of hasMany (many-to-one).
     *
     * @param class-string<Model> $modelClass Related model
     * @param string              $foreignKey FK column on this table
     * @param string              $ownerKey   PK column on related table
     */
    public static function belongsTo(string $modelClass, string $foreignKey, string $ownerKey): self
    {
        $rel = new self();
        $rel->type = self::TYPE_BELONGS_TO;
        $rel->modelClass = $modelClass;
        $rel->foreignKey = $foreignKey;
        $rel->localKey = $ownerKey;

        return $rel;
    }

    /**
     * Many-to-many via a pivot table.
     *
     * Note: $pivotTable must be the full table name, including any prefix.
     *
     * @param class-string<Model> $modelClass      Related model
     * @param string              $pivotTable      Full pivot table name (with prefix)
     * @param string              $foreignPivotKey Pivot column for this model
     * @param string              $relatedPivotKey Pivot column for related model
     * @param string              $parentKey       PK column on this model
     * @param string              $relatedKey      PK column on related model
     */
    public static function belongsToMany(
        string $modelClass,
        string $pivotTable,
        string $foreignPivotKey,
        string $relatedPivotKey,
        string $parentKey,
        string $relatedKey
    ): self {
        $rel = new self();
        $rel->type = self::TYPE_BELONGS_TO_MANY;
        $rel->modelClass = $modelClass;
        $rel->pivotTable = $pivotTable;
        $rel->foreignPivotKey = $foreignPivotKey;
        $rel->relatedPivotKey = $relatedPivotKey;
        $rel->parentKey = $parentKey;
        $rel->relatedKey = $relatedKey;

        return $rel;
    }

    /**
     * Attach the parent model instance to this relation.
     */
    public function setParent(Model $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get the relation type.
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Get the related model class.
     */
    public function getModelClass(): string
    {
        return $this->modelClass;
    }

    /**
     * Get the foreign key (for hasMany/belongsTo).
     */
    public function getForeignKey(): ?string
    {
        return $this->foreignKey;
    }

    /**
     * Get the local key (for hasMany/belongsTo).
     */
    public function getLocalKey(): ?string
    {
        return $this->localKey;
    }

    /**
     * Get the pivot table name.
     */
    public function getPivotTable(): ?string
    {
        return $this->pivotTable;
    }

    /**
     * Get foreign pivot key (for belongsToMany).
     */
    public function getForeignPivotKey(): ?string
    {
        return $this->foreignPivotKey;
    }

    /**
     * Get related pivot key (for belongsToMany).
     */
    public function getRelatedPivotKey(): ?string
    {
        return $this->relatedPivotKey;
    }

    /**
     * Get parent key (for belongsToMany).
     */
    public function getParentKey(): ?string
    {
        return $this->parentKey;
    }

    /**
     * Get related key (for belongsToMany).
     */
    public function getRelatedKey(): ?string
    {
        return $this->relatedKey;
    }

    /**
     * Fetch related records for hasMany / belongsTo.
     *
     * @param  int[]            $ids
     * @throws RuntimeException
     * @return Model[]
     */
    public function fetch(array $ids): array
    {
        if ($this->getType() === self::TYPE_BELONGS_TO_MANY) {
            throw new RuntimeException('fetch() not supported on a many-to-many relation.');
        }

        return $this->getModelClass()::query()
            ->whereIn($this->getForeignKey(), $ids)
            ->get();
    }

    /**
     * Add pivot entries (avoiding duplicates).
     *
     * @param mixed ...$items int IDs or Model instances
     */
    public function attach(mixed ...$items): void
    {
        $db = Model::db();
        $parentVal = $this->parent->{$this->getParentKey()};
        $table = "`{$this->getPivotTable()}`";
        $fk = "`{$this->getForeignPivotKey()}`";
        $rk = "`{$this->getRelatedPivotKey()}`";

        foreach ($items as $item) {
            $relatedVal = $item instanceof Model
                ? $item->{$this->getRelatedKey()}
                : (int) $item;

            $exists = $db->query(
                "SELECT 1
                   FROM {$table}
                  WHERE {$fk} = ?
                    AND {$rk} = ?
                  LIMIT 1",
                [$parentVal, $relatedVal]
            )->count() > 0;

            if ($exists) {
                continue;
            }

            $db->query(
                "INSERT INTO {$table} ({$fk}, {$rk})
                 VALUES (?, ?)",
                [$parentVal, $relatedVal]
            );
        }
    }

    /**
     * Remove pivot entries; if no args, remove all for this parent.
     *
     * @param mixed ...$items int IDs or Model instances
     */
    public function detach(mixed ...$items): void
    {
        $db = Model::db();
        $parentVal = $this->parent->{$this->getParentKey()};
        $table = "`{$this->getPivotTable()}`";
        $fk = "`{$this->getForeignPivotKey()}`";
        $rk = "`{$this->getRelatedPivotKey()}`";

        if (empty($items)) {
            $db->query(
                "DELETE
                   FROM {$table}
                  WHERE {$fk} = ?",
                [$parentVal]
            );

            return;
        }

        $ids = array_map(fn ($i) => $i instanceof Model
            ? $i->{$this->getRelatedKey()}
            : (int) $i, $items);
        $ph = implode(',', array_fill(0, count($ids), '?'));

        $db->query(
            "DELETE
               FROM {$table}
              WHERE {$fk} = ?
                AND {$rk} IN ({$ph})",
            array_merge([$parentVal], $ids)
        );
    }

    /**
     * Sync pivot: leave only the given IDs.
     *
     * @param int[] $wanted
     */
    public function sync(array $wanted): void
    {
        $db = Model::db();
        $parentId = $this->parent->{$this->getParentKey()};

        $rows = $db->query(
            "SELECT `{$this->getRelatedPivotKey()}` AS id
               FROM `{$this->getPivotTable()}`
              WHERE `{$this->getForeignPivotKey()}` = ?",
            [$parentId],
            true
        )->results();

        $current = array_map(fn ($r) => (int) $r->id, $rows);
        $toAdd = array_diff($wanted, $current);
        $toRemove = array_diff($current, $wanted);

        if (!empty($toRemove)) {
            $this->detach(...$toRemove);
        }
        if (!empty($toAdd)) {
            $this->attach(...$toAdd);
        }
    }

    /**
     * Toggle a single pivot entry.
     *
     * @param mixed $item int ID or Model instance
     */
    public function toggle(mixed $item): void
    {
        $id = $item instanceof Model
            ? $item->{$this->getRelatedKey()}
            : (int) $item;
        $db = Model::db();
        $parentId = $this->parent->{$this->getParentKey()};

        $exists = $db->query(
            "SELECT 1
               FROM `{$this->getPivotTable()}`
              WHERE `{$this->getForeignPivotKey()}` = ?
                AND `{$this->getRelatedPivotKey()}` = ?
              LIMIT 1",
            [$parentId, $id]
        )->count() > 0;

        $exists ? $this->detach($id) : $this->attach($id);
    }
}
