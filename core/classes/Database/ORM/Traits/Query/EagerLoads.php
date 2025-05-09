<?php
declare(strict_types=1);

namespace Database\ORM\Traits\Query;

use Database\ORM\Relation;
use Model;

/**
 * Trait EagerLoads
 *
 * Handles nested eager loading for belongsToMany, hasMany, belongsTo.
 */
trait EagerLoads
{
    /**
     * Eagerly load all requested relations, including nested ones.
     *
     * @param  Model[] $models
     * @return Model[]
     */
    protected function eagerLoad(array $models): array
    {
        foreach ($this->with as $relName => $nested) {
            $prototype = new $this->modelClass();
            $relDef = $prototype->{$relName}();
            if (!$relDef instanceof Relation) {
                continue;
            }

            // --- many-to-many handling ---
            if ($relDef->type === 'belongsToMany') {
                // 1) Collect unique parent IDs
                $parentIds = array_unique(array_map(
                    fn ($m) => $m->{$relDef->parentKey},
                    $models
                ));

                // If no parents, assign empty arrays
                if (!$parentIds) {
                    foreach ($models as $m) {
                        $m->setRelation($relName, []);
                    }
                    continue;
                }

                // 2) Query pivot for mappings
                $phs = implode(',', array_fill(0, count($parentIds), '?'));
                $sql = "SELECT `{$relDef->foreignPivotKey}` AS parent_id,
                           `{$relDef->relatedPivotKey}` AS related_id
                    FROM `{$relDef->pivotTable}`
                    WHERE `{$relDef->foreignPivotKey}` IN ($phs)";

                $rows = Model::db()->query($sql, $parentIds, true)->results();

                // 3) Group related IDs by parent ID
                $map = [];
                $allRelated = [];
                foreach ($rows as $r) {
                    $map[$r->parent_id][] = $r->related_id;
                    $allRelated[] = $r->related_id;
                }
                $allRelated = array_unique($allRelated);

                // 4) Load related models in one go
                $qb = $relDef->model::query()
                    ->whereIn($relDef->relatedKey, $allRelated);
                if ($nested) {
                    $qb = $qb->with($nested);
                }
                $children = $qb->get();

                // Index children by related key
                $indexed = [];
                foreach ($children as $c) {
                    $indexed[$c->{$relDef->relatedKey}] = $c;
                }

                // 5) Assign each parent its related models
                foreach ($models as $parent) {
                    $pid = $parent->{$relDef->parentKey};
                    $list = [];
                    foreach ($map[$pid] ?? [] as $rid) {
                        if (isset($indexed[$rid])) {
                            $list[] = $indexed[$rid];
                        }
                    }
                    $parent->setRelation($relName, $list);
                }

                continue;
            }

            // --- existing hasMany / belongsTo logic ---
            $ids = array_unique(array_map(
                fn ($m) => $m->{$relDef->localKey},
                $models
            ));

            $qb = $relDef->model::query()
                ->whereIn($relDef->foreignKey, $ids);

            if ($nested) {
                $qb = $qb->with($nested);
            }

            $children = $qb->get();
            $grouped = [];

            if ($relDef->type === 'hasMany') {
                foreach ($children as $c) {
                    $grouped[$c->{$relDef->foreignKey}][] = $c;
                }
            } else {
                foreach ($children as $c) {
                    $grouped[$c->{$relDef->foreignKey}] = $c;
                }
            }

            foreach ($models as $parent) {
                $value = $relDef->type === 'hasMany'
                    ? ($grouped[$parent->{$relDef->localKey}] ?? [])
                    : ($grouped[$parent->{$relDef->localKey}] ?? null);
                $parent->setRelation($relName, $value);
            }
        }

        return $models;
    }
}
