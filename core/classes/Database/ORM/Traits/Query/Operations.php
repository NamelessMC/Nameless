<?php

declare(strict_types=1);

namespace Database\ORM\Traits\Query;

use Database\ORM\Query;
use Database\ORM\Support\Collection;
use Model;
use RuntimeException;

/**
 * @mixin Query
 */
trait Operations
{
    /**
     * Retrieve all records.
     *
     * @return Collection of Model
     */
    public function get(): Collection
    {
        $rows = Model::db()
            ->query($this->buildSelect(), $this->params, true)
            ->results();

        $models = array_map(fn ($r) => new $this->modelClass((array) $r), $rows);
        $loaded = $this->with ? $this->eagerLoad($models) : $models;

        return new Collection($loaded);
    }

    /**
     * First model or null.
     */
    public function first(): mixed
    {
        return $this->limit(1)->get()[0] ?? null;
    }

    /**
     * As first(), But throws an exception if nothing is found.
     */
    public function firstOrFail(): mixed
    {
        $result = $this->first();
        if ($result === null) {
            throw new RuntimeException('Record not found');
        }

        return $result;
    }

    /**
     * Find by primary key.
     */
    public function find(int $id): mixed
    {
        return $this->where($this->primaryKey, '=', $id)->first();
    }

    /**
     * Chunk-processing: Loading packs by $size.
     */
    public function chunk(int $size, callable $callback): void
    {
        $page = 1;
        do {
            $batch = $this->limit($size)->offset(($page - 1) * $size)->get();
            $callback($batch);
            $page++;
        } while (count($batch) === $size);
    }

    /**
     * Return array of values of one column.
     */
    public function pluck(string $column, ?string $keyColumn = null): array
    {
        $cols = "`{$column}`" . ($keyColumn ? ", `{$keyColumn}`" : '');
        $sql = "SELECT {$cols} FROM {$this->table}"
            . ($this->wheres ? ' WHERE ' . implode(' AND ', $this->wheres) : '')
            . ($this->orderBy ? " {$this->orderBy}" : '')
            . ($this->limit !== null ? " LIMIT {$this->limit}" : '')
            . ($this->offset !== null ? " OFFSET {$this->offset}" : '');

        $rows = Model::db()->query($sql, $this->params, true)->results();

        $result = [];
        foreach ($rows as $row) {
            $val = $row->{$column};
            if ($keyColumn) {
                $result[$row->{$keyColumn}] = $val;
            } else {
                $result[] = $val;
            }
        }

        return $result;
    }

    /**
     * Is there a record that meets the conditions?
     */
    public function exists(): bool
    {
        return $this->count() > 0;
    }

    /**
     * Pagination: returns ['data'=>…, 'total'=>…, 'per_page'=>…, 'current_page'=>…, 'last_page'=>…].
     */
    public function paginate(int $perPage, int $page = 1): array
    {
        $total = $this->count();
        $data = $this->limit($perPage)
            ->offset(($page - 1) * $perPage)
            ->get();

        return [
            'data' => $data,
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'last_page' => (int) ceil($total / $perPage),
        ];
    }

    /**
     * Return an array of attributes of all models.
     *
     * @return array<int,array<string,mixed>>
     */
    public function toArray(): array
    {
        return array_map(fn ($model) => $model->toArray(), (array) $this->get());
    }

    /**
     * Return the JSON-representation of the Model array.
     */
    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_UNESCAPED_UNICODE);
    }
}
