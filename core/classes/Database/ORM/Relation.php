<?php
declare(strict_types=1);

namespace Database\ORM;

use Model;

/**
 * Encapsulates relationship metadata between two models.
 */
class Relation
{
    public string $type;
    public string $model;

    // used by hasMany / belongsTo
    public ?string $foreignKey;
    public ?string $localKey;

    // used by belongsToMany
    public ?string $pivotTable;
    public ?string $foreignPivotKey;
    public ?string $relatedPivotKey;
    public ?string $parentKey;
    public ?string $relatedKey;

    /**
     * Constructor for relation metadata.
     *
     * @param 'hasMany'|'belongsTo'|'belongsToMany' $type
     * @param class-string<Model> $model
     * @param string $a For belongsToMany: pivotTable; otherwise: foreignKey
     * @param string $b For belongsToMany: foreignPivotKey; otherwise: localKey
     * @param string|null $c For belongsToMany: relatedPivotKey
     * @param string|null $d For belongsToMany: parentKey
     * @param string|null $e For belongsToMany: relatedKey
     */
    public function __construct(
        string  $type,
        string  $model,
        string  $a,
        string  $b,
        ?string $c = null,
        ?string $d = null,
        ?string $e = null
    )
    {
        $this->type = $type;
        $this->model = $model;

        if ($type === 'belongsToMany') {
            // Configure pivot settings
            $this->pivotTable = Model::$prefix . $a;
            $this->foreignPivotKey = $b;
            $this->relatedPivotKey = $c;
            $this->parentKey = $d;
            $this->relatedKey = $e;

            // Clear single-relation fields
            $this->foreignKey = null;
            $this->localKey = null;
        } else {
            // Configure hasMany or belongsTo
            $this->pivotTable =
            $this->foreignPivotKey =
            $this->relatedPivotKey =
            $this->parentKey =
            $this->relatedKey = null;

            $this->foreignKey = $a;
            $this->localKey = $b;
        }
    }

    /**
     * Eager-loads records matching any of the given keys.
     *
     * @param array<int|string> $ids
     * @return Model[]
     */
    public function fetch(array $ids): array
    {
        return $this->model::query()
            ->whereIn($this->foreignKey, $ids)
            ->get();
    }
}
