<?php

class QueryBuilder
{
    protected string $table;
    protected string $primaryKey;
    protected string $modelClass;

    protected array $wheres = [];
    protected array $params = [];
    protected ?string $orderBy = null;
    protected ?int $limit = null;
    protected array $relations = [];

    public function __construct(string $table, string $primaryKey, string $modelClass)
    {
        $this->table = $table;
        $this->primaryKey = $primaryKey;
        $this->modelClass = $modelClass;
    }

    public function where(string $column, string $operator, mixed $value): static
    {
        $this->wheres[] = "`$column` $operator ?";
        $this->params[] = $value;
        return $this;
    }

    public function orderBy(string $column, string $direction = 'ASC'): static
    {
        $this->orderBy = "ORDER BY `$column` $direction";
        return $this;
    }

    public function limit(int $limit): static
    {
        $this->limit = $limit;
        return $this;
    }

    public function with(array|string $relations): static
    {
        $this->relations = array_merge($this->relations, (array)$relations);
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

    public function get(): array
    {
        $results = Model::db()->query($this->buildSelect(), $this->params, true)->results();

        return array_map(fn($item) => $this->toModel($item), $results);
    }

    public function first(): ?Model
    {
        return $this->limit(1)->get()[0] ?? null;
    }

    public function find(int $id): ?Model
    {
        return $this->where($this->primaryKey, '=', $id)->first();
    }

    public function create(array $data): Model
    {
        $keys = implode('`,`', array_keys($data));
        $placeholders = implode(',', array_fill(0, count($data), '?'));

        Model::db()->query("INSERT INTO {$this->table} (`$keys`) VALUES ($placeholders)", array_values($data));

        $id = Model::db()->lastId();
        $data[$this->primaryKey] = $id;

        return $this->toModel((object)$data);
    }

    public function update(array $data, mixed $id): bool
    {
        $set = implode(',', array_map(fn($col) => "`$col` = ?", array_keys($data)));
        $sql = "UPDATE {$this->table} SET {$set} WHERE `{$this->primaryKey}` = ?";
        return !Model::db()->query($sql, [...array_values($data), $id])->error();
    }

    public function delete(mixed $id): bool
    {
        $sql = "DELETE FROM {$this->table} WHERE `{$this->primaryKey}` = ?";
        return !Model::db()->query($sql, [$id])->error();
    }

    public function increment(string $column, int $amount, mixed $id): bool
    {
        $sql = "UPDATE {$this->table} SET `$column` = `$column` + ? WHERE `{$this->primaryKey}` = ?";
        return !Model::db()->query($sql, [$amount, $id])->error();
    }

    public function decrement(string $column, int $amount, mixed $id): bool
    {
        return $this->increment($column, -$amount, $id);
    }

    protected function toModel(object $attributes): Model
    {
        $model = new $this->modelClass((array)$attributes);

        foreach ($this->relations as $relation) {
            if (method_exists($model, $relation)) {
                $model->{$relation} = $model->{$relation}($model);
            }
        }

        return $model;
    }
}





