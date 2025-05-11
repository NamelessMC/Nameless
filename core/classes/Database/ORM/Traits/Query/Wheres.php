<?php

declare(strict_types=1);

namespace Database\ORM\Traits\Query;

use Closure;
use Database\ORM\Query;
use Database\ORM\Relation;
use RuntimeException;

/**
 * @mixin Query
 */
trait Wheres
{
    /**
     * Add a basic WHERE clause.
     *
     * @param  string $col     Column name
     * @param  mixed  $opOrVal Operator or value (if the second parameter is the value)
     * @param  mixed  $val     Value (if three parameters are transferred)
     * @return $this
     */
    public function where(string $col, mixed $opOrVal, mixed $val = null): static
    {
        if (func_num_args() === 2) {
            $op = '=';
            $val = $opOrVal;
        } else {
            $op = $opOrVal;
        }
        $this->wheres[] = "`{$col}` {$op} ?";
        $this->params[] = $val;

        return $this;
    }

    /**
     * Filters by a related model.
     */
    public function whereHas(string $relation, ?Closure $callback = null): static
    {
        $prototype = new $this->modelClass();
        $rel = $prototype->{$relation}();
        $parentTable = $this->table;
        $parentKey = $this->primaryKey;
        $relatedClass = $rel->getModelClass();
        $relatedTable = $relatedClass::table();

        if ($rel->getType() === Relation::TYPE_HAS_MANY) {
            $fk = $rel->getForeignKey();
            $subSql = "SELECT 1 FROM `{$relatedTable}` WHERE `{$relatedTable}`.`{$fk}` = `{$parentTable}`.`{$parentKey}`";
            $params = [];
            if ($callback) {
                $subQuery = $relatedClass::query()->where("`{$fk}`", '=', 0);
                $subQuery->wheres = [];
                $subQuery->params = [];
                $callback($subQuery);
                if ($subQuery->wheres) {
                    $subSql .= ' AND ' . implode(' AND ', $subQuery->wheres);
                    $params = $subQuery->params;
                }
            }
        } elseif ($rel->getType() === Relation::TYPE_BELONGS_TO_MANY) {
            $pivot = $rel->getPivotTable();
            $fpivot = $rel->getForeignPivotKey();
            $rpivot = $rel->getRelatedPivotKey();
            $relatedKey = $rel->getRelatedKey();
            $subSql = "SELECT 1
                       FROM `{$pivot}` AS p
                       JOIN `{$relatedTable}` AS r
                         ON p.`{$rpivot}` = r.`{$relatedKey}`
                       WHERE p.`{$fpivot}` = `{$parentTable}`.`{$parentKey}`";
            $params = [];
            if ($callback) {
                $subQuery = $relatedClass::query()->where("`{$relatedKey}`", '=', 0);
                $subQuery->wheres = [];
                $subQuery->params = [];
                $callback($subQuery);
                if ($subQuery->wheres) {
                    $extra = array_map(fn ($w) => preg_replace('/^`r`\./', 'r.', $w), $subQuery->wheres);
                    $subSql .= ' AND ' . implode(' AND ', $extra);
                    $params = $subQuery->params;
                }
            }
        } else {
            throw new RuntimeException("whereHas not supported for relation type {$rel->getType()}");
        }

        $this->whereRaw("EXISTS ({$subSql})", $params);

        return $this;
    }

    /**
     * Add a raw WHERE clause.
     */
    public function whereRaw(string $sql, array $params = []): static
    {
        $this->wheres[] = $sql;
        $this->params = array_merge($this->params, $params);

        return $this;
    }

    /**
     * Add a WHERE IN (...) clause.
     */
    public function whereIn(string $col, array $vals): static
    {
        $placeholders = implode(',', array_fill(0, count($vals), '?'));
        $this->wheres[] = "`{$col}` IN ({$placeholders})";
        $this->params = array_merge($this->params, $vals);

        return $this;
    }
}
