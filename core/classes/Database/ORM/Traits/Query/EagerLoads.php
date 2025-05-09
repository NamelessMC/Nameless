<?php

declare(strict_types=1);

namespace Database\ORM\Traits\Query;

use Database\ORM\Relation;
use Model;

/**
 * Trait EagerLoads.
 *
 * Handles nested eager loading for belongsToMany, hasMany and belongsTo relations.
 */
trait EagerLoads
{
    /**
     * Eagerly load all requested relations, including nested ones.
     *
     * @param Model[] $models
     * @return Model[]
     */
    protected function eagerLoad(array $models): array
    {
        // If there are no models, nothing to do
        if (empty($models)) {
            return [];
        }

        foreach ($this->with as $relName => $nested) {
            // Instantiate a prototype to get the Relation definition
            $prototype = new $this->modelClass();
            $relDef = $prototype->{$relName}();

            // Skip if this isn't a Relation
            if (!$relDef instanceof Relation) {
                continue;
            }

            // --- many-to-many handling ---
            if ($relDef->type === 'belongsToMany') {
                // 1) Collect unique parent IDs
                $parentIds = array_unique(array_map(
                    fn($m) => $m->{$relDef->parentKey},
                    $models
                ));

                // 2) If no parent IDs, assign empty arrays and continue
                if (empty($parentIds)) {
                    foreach ($models as $m) {
                        $m->setRelation($relName, []);
                    }
                    continue;
                }

                // 3) Query pivot table for mappings
                $placeholders = implode(',', array_fill(0, count($parentIds), '?'));
                $sql = "SELECT `{$relDef->foreignPivotKey}` AS parent_id, `{$relDef->relatedPivotKey}` AS related_id
                        FROM `{$relDef->pivotTable}`
                        WHERE `{$relDef->foreignPivotKey}` IN ($placeholders)";
                $rows = Model::db()->query($sql, $parentIds, true)->results();

                // 4) Group related IDs by parent ID and collect all related IDs
                $map = [];
                $allRelatedIds = [];
                foreach ($rows as $row) {
                    $map[$row->parent_id][] = $row->related_id;
                    $allRelatedIds[] = $row->related_id;
                }
                $allRelatedIds = array_unique($allRelatedIds);

                // 5) If no related IDs, assign empty arrays and continue
                if (empty($allRelatedIds)) {
                    foreach ($models as $m) {
                        $m->setRelation($relName, []);
                    }
                    continue;
                }

                // 6) Load related models in one go
                $qb = $relDef->model::query()
                    ->whereIn($relDef->relatedKey, $allRelatedIds);
                if (!empty($nested)) {
                    $qb = $qb->with($nested);
                }
                $children = $qb->get();

                // 7) Index children by related key for quick lookup
                $indexed = [];
                foreach ($children as $child) {
                    $indexed[$child->{$relDef->relatedKey}] = $child;
                }

                // 8) Assign each parent its related models
                foreach ($models as $parent) {
                    $pid = $parent->{$relDef->parentKey};
                    $related = [];
                    foreach ($map[$pid] ?? [] as $rid) {
                        if (isset($indexed[$rid])) {
                            $related[] = $indexed[$rid];
                        }
                    }
                    $parent->setRelation($relName, $related);
                }

                continue;
            }

            // --- hasMany / belongsTo handling ---
            // 1) Collect unique local keys from parent models
            $keys = array_unique(array_map(
                fn($m) => $m->{$relDef->localKey},
                $models
            ));

            // 2) If no keys, assign default empty/null and continue
            if (empty($keys)) {
                foreach ($models as $m) {
                    $m->setRelation($relName, $relDef->type === 'hasMany' ? [] : null);
                }
                continue;
            }

            // 3) Query related models based on the foreign key
            $qb = $relDef->model::query()
                ->whereIn($relDef->foreignKey, $keys);
            if (!empty($nested)) {
                $qb = $qb->with($nested);
            }
            $children = $qb->get();

            // 4) Group children by foreign key (arrays for hasMany, single item for belongsTo)
            $grouped = [];
            foreach ($children as $child) {
                $fk = $child->{$relDef->foreignKey};
                if ($relDef->type === 'hasMany') {
                    $grouped[$fk][] = $child;
                } else {
                    $grouped[$fk] = $child;
                }
            }

            // 5) Assign grouped results to each parent
            foreach ($models as $parent) {
                $key = $parent->{$relDef->localKey};
                $value = $relDef->type === 'hasMany'
                    ? ($grouped[$key] ?? [])
                    : ($grouped[$key] ?? null);
                $parent->setRelation($relName, $value);
            }
        }

        return $models;
    }
}
