<?php

/**
 * Builds & executes queries, now with nested ->with() support.
 *
 * @template TModel of Model
 */
class QueryBuilder
{
    protected string $table;
    protected string $primaryKey;
    /** @var class-string<TModel> */
    protected string $modelClass;

    /**
     * relationName => [ nested, relations ]
     * e.g. [ 'statistics' => ['server'], 'foo' => [] ].
     * @var array<string,string[]>
     */
    protected array $with = [];

    protected array $wheres = [];
    protected array $params = [];
    protected ?string $orderBy = null;
    protected ?int $limit = null;

    /**
     * @param string $table
     * @param string $primaryKey
     * @param class-string<TModel> $modelClass
     */
    public function __construct(string $table, string $primaryKey, string $modelClass)
    {
        $this->table = $table;
        $this->primaryKey = $primaryKey;
        $this->modelClass = $modelClass;
    }

    /**
     * Accepts dot notation, e.g. 'statistics.server'.
     *
     * @param array|string $relations
     * @return QueryBuilder
     */
    public function with(array|string $relations): static
    {
        foreach ((array)$relations as $r) {
            if (str_contains($r, '.')) {
                [$root, $child] = explode('.', $r, 2);
                $this->with[$root][] = $child;
            } else {
                $this->with[$r] = $this->with[$r] ?? [];
            }
        }

        return $this;
    }

    public function where(string $col, string $op, mixed $val): static
    {
        $this->wheres[] = "`{$col}` {$op} ?";
        $this->params[] = $val;

        return $this;
    }

    public function whereIn(string $col, array $vals): static
    {
        $ph = implode(',', array_fill(0, count($vals), '?'));
        $this->wheres[] = "`{$col}` IN ({$ph})";
        $this->params = array_merge($this->params, $vals);

        return $this;
    }

    /**
     * Retrieve a single column’s values from the result set.
     *
     * @param string $column The column to retrieve.
     * @param string|null $keyColumn If provided, use this column’s values as the returned array’s keys.
     * @return array  List of values (or key=>value pairs).
     */
    public function pluck(string $column, ?string $keyColumn = null): array
    {
        $cols = "`{$column}`"
            . ($keyColumn ? ", `{$keyColumn}`" : "");
        $sql = "SELECT {$cols} FROM {$this->table}";

        if ($this->wheres) {
            $sql .= ' WHERE ' . implode(' AND ', $this->wheres);
        }
        if ($this->orderBy) {
            $sql .= " {$this->orderBy}";
        }
        if ($this->limit !== null) {
            $sql .= " LIMIT {$this->limit}";
        }

        $rows = Model::db()
            ->query($sql, $this->params, true)
            ->results();

        $result = [];
        foreach ($rows as $row) {
            $value = $row->{$column};
            if ($keyColumn) {
                $result[$row->{$keyColumn}] = $value;
            } else {
                $result[] = $value;
            }
        }

        return $result;
    }

    public function orderBy(string $col, string $dir = 'ASC'): static
    {
        $this->orderBy = "ORDER BY `{$col}` {$dir}";

        return $this;
    }

    public function limit(int $l): static
    {
        $this->limit = $l;

        return $this;
    }

    protected function buildSelect(): string
    {
        $sql = "SELECT * FROM {$this->table}";
        if ($this->wheres) {
            $sql .= ' WHERE ' . implode(' AND ', $this->wheres);
        }
        if ($this->orderBy) {
            $sql .= " {$this->orderBy}";
        }
        if ($this->limit !== null) {
            $sql .= " LIMIT {$this->limit}";
        }

        return $sql;
    }

    /**
     * @return TModel[]
     */
    public function get(): array
    {
        $rows = Model::db()
            ->query($this->buildSelect(), $this->params, true)
            ->results();

        $models = array_map(fn($r) => new $this->modelClass((array)$r), $rows);

        if ($this->with) {
            $models = $this->eagerLoad($models);
        }

        return $models;
    }

    /**
     * @return TModel|null
     */
    public function first()
    {
        return $this->limit(1)->get()[0] ?? null;
    }

    /**
     * @param int $id
     * @return TModel|null
     */
    public function find(int $id)
    {
        return $this->where($this->primaryKey, '=', $id)->first();
    }

    /**
     * @param array $data
     * @return TModel
     */
    public function create(array $data)
    {
        $cols = implode('`,`', array_keys($data));
        $phs = implode(',', array_fill(0, count($data), '?'));

        Model::db()->query(
            "INSERT INTO {$this->table} (`{$cols}`) VALUES ({$phs})",
            array_values($data)
        );

        $id = Model::db()->lastId();
        $data[$this->primaryKey] = $id;

        return new $this->modelClass($data);
    }

    public function update(array $data, mixed $id): bool
    {
        $set = implode(',', array_map(fn($c) => "`{$c}` = ?", array_keys($data)));
        $sql = "UPDATE {$this->table} SET {$set} WHERE `{$this->primaryKey}` = ?";

        return !Model::db()->query($sql, [...array_values($data), $id])->error();
    }

    public function delete(mixed $id): bool
    {
        return !Model::db()
            ->query("DELETE FROM {$this->table} WHERE `{$this->primaryKey}` = ?", [$id])
            ->error();
    }

    public function increment(string $col, int $amt, mixed $id): bool
    {
        return !Model::db()->query(
            "UPDATE {$this->table} 
             SET `{$col}` = `{$col}` + ? 
             WHERE `{$this->primaryKey}` = ?",
            [$amt, $id]
        )->error();
    }

    public function decrement(string $col, int $amt, mixed $id): bool
    {
        return $this->increment($col, -$amt, $id);
    }

    /**
     * Return the raw SQL (with placeholders) that would be executed.
     */
    public function toSql(): string
    {
        return $this->buildSelect();
    }

    /**
     * Return the array of bound parameters for the SQL.
     *
     * @return array<int,mixed>
     */
    public function getBindings(): array
    {
        return $this->params;
    }

    /**
     * Eagerly load all requested relations, including nested ones.
     *
     * @param Model[] $models
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

            // get the key values to match:
            $ids = $relDef->type === 'hasMany'
                ? array_map(fn($m) => $m->{$relDef->localKey}, $models)
                : array_map(fn($m) => $m->{$relDef->localKey}, $models);
            $ids = array_unique($ids);

            // build the child query
            $qb = $relDef->model::query()
                ->whereIn($relDef->foreignKey, $ids);
            if ($nested) {
                $qb = $qb->with($nested);
            }
            $children = $qb->get();

            // group them
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

            // attach to parents
            foreach ($models as $parent) {
                $value = $relDef->type === 'hasMany'
                    ? ($grouped[$parent->{$relDef->localKey}] ?? [])
                    : ($grouped[$parent->{$relDef->localKey}] ?? null);
                // use our setter so __get picks it up
                $parent->setRelation($relName, $value);
            }
        }

        return $models;
    }
}
