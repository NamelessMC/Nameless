<?php

/**
 * Encapsulates relationship metadata between two models.
 */
class Relation
{
    /**
     * @param 'hasMany'|'belongsTo' $type        Type of relation.
     * @param class-string<Model>   $model       Fully-qualified related model class.
     * @param string                $foreignKey  Column name in the related (child) table for hasMany,
     *                                           or in this (child) table for belongsTo.
     * @param string                $localKey    Column name in this (parent) table for hasMany,
     *                                           or in the related (parent) table for belongsTo.
     */
    public function __construct(
        public string $type,
        public string $model,
        public string $foreignKey,
        public string $localKey
    ) {}

    /**
     * Eager-loads all related records matching any of the given keys.
     *
     * @param  array<int|string> $ids  List of key values to match against $foreignKey.
     * @return Model[]                  Array of related model instances.
     */
    public function fetch(array $ids): array
    {
        return $this->model::query()
            ->whereIn($this->foreignKey, $ids)
            ->get();
    }
}
