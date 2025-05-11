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
trait Relations
{
    /**
     * Accepts dot notation, e.g. 'statistics.server'.
     *
     * @param  array|string $relations
     * @return $this
     */
    public function with(array|string $relations): static
    {
        foreach ((array) $relations as $r) {
            if (str_contains($r, '.')) {
                [$root, $child] = explode('.', $r, 2);
                $this->with[$root][] = $child;
            } else {
                $this->with[$r] = $this->with[$r] ?? [];
            }
        }

        return $this;
    }

    /**
     * Eagerly load all requested relations, including nested ones.
     *
     * @param  Model[] $models
     * @return Model[]
     */
    protected function eagerLoad(array $models): array
    {
        if (empty($models)) {
            return [];
        }

        foreach ($this->with as $relName => $nested) {
            $prototype = new $this->modelClass();
            $relDef = $prototype->{$relName}();

            if (!$relDef instanceof Relation) {
                continue;
            }

            $type = $relDef->getType();

            if ($type === Relation::TYPE_BELONGS_TO_MANY) {
                // many-to-many
                $parentKey = $relDef->getParentKey();
                $foreignPivotKey = $relDef->getForeignPivotKey();
                $relatedPivotKey = $relDef->getRelatedPivotKey();
                $pivotTable = $relDef->getPivotTable();
                $relatedKey = $relDef->getRelatedKey();
                $relatedModel = $relDef->getModelClass();

                $parentIds = array_unique(array_map(
                    fn ($m) => $m->{$parentKey},
                    $models
                ));
                $ph = implode(',', array_fill(0, count($parentIds), '?'));
                $sql = "SELECT `{$foreignPivotKey}` AS parent_id, `{$relatedPivotKey}` AS related_id
                         FROM `{$pivotTable}`
                         WHERE `{$foreignPivotKey}` IN ({$ph})";
                $rows = Model::db()->query($sql, $parentIds, true)->results();

                $map = [];
                $all = [];
                foreach ($rows as $r) {
                    $map[$r->parent_id][] = $r->related_id;
                    $all[] = $r->related_id;
                }
                $all = array_unique($all);

                if (empty($all)) {
                    foreach ($models as $m) {
                        $m->setRelation($relName, []);
                    }
                    continue;
                }

                $qb = $relatedModel::query()->whereIn($relatedKey, $all);
                $children = !empty($nested) ? $qb->with($nested)->get() : $qb->get();

                $indexed = [];
                foreach ($children as $c) {
                    $indexed[$c->{$relatedKey}] = $c;
                }

                foreach ($models as $parent) {
                    $pid = $parent->{$parentKey};
                    $related = [];
                    foreach ($map[$pid] ?? [] as $rid) {
                        if (isset($indexed[$rid])) {
                            $related[] = $indexed[$rid];
                        }
                    }
                    $parent->setRelation($relName, $related);
                }
            } else {
                // hasMany / belongsTo
                $foreignKey = $relDef->getForeignKey();
                $localKey = $relDef->getLocalKey();
                $modelClass = $relDef->getModelClass();

                $keys = array_unique(array_map(
                    fn ($m) => $m->{$localKey},
                    $models
                ));

                $qb = $modelClass::query()->whereIn($foreignKey, $keys);
                $children = !empty($nested) ? $qb->with($nested)->get() : $qb->get();

                $grouped = [];
                foreach ($children as $child) {
                    $fk = $child->{$foreignKey};
                    if ($type === Relation::TYPE_HAS_MANY) {
                        $grouped[$fk][] = $child;
                    } else {
                        $grouped[$fk] = $child;
                    }
                }

                foreach ($models as $parent) {
                    $key = $parent->{$localKey};
                    $value = $type === Relation::TYPE_HAS_MANY
                        ? ($grouped[$key] ?? [])
                        : ($grouped[$key] ?? null);
                    $parent->setRelation($relName, $value);
                }
            }
        }

        return $models;
    }
}
