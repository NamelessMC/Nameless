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
     * Retrieve all records as a Collection of models.
     *
     * @return Collection<Model>
     */
    public function get(): Collection
    {
        $rows = Model::db()
            ->query($this->buildSelect(), $this->params, true)
            ->results();

        $models = array_map(fn($r) => new $this->modelClass((array)$r), $rows);
        $loaded = $this->with
            ? $this->eagerLoad($models)
            : $models;

        return new Collection($loaded);
    }

    /**
     * First model or null.
     *
     * @return Model|null
     */
    public function first(): Model|null
    {
        return $this->limit(1)->get()->all()[0] ?? null;
    }

    /**
     * First model or throw.
     *
     * @return Model
     * @throws RuntimeException
     */
    public function firstOrFail(): Model
    {
        $result = $this->first();
        if ($result === null) {
            throw new RuntimeException('Record not found');
        }

        return $result;
    }

    /**
     * Find by primary key.
     *
     * @param int $id
     * @return Model|null
     */
    public function find(int $id): Model|null
    {
        return $this->where($this->primaryKey, '=', $id)->first();
    }

    /**
     * Chunk processing: load in batches of $size.
     *
     * @param int $size
     * @param callable $callback Receives Collection<Model>
     */
    public function chunk(int $size, callable $callback): void
    {
        $page = 1;
        do {
            $batch = $this->limit($size)
                ->offset(($page - 1) * $size)
                ->get();
            $callback($batch);
            $page++;
        } while (count($batch) === $size);
    }

    /**
     * Return array of values of one column.
     *
     * @param string $column
     * @param string|null $keyColumn
     * @return array<int|string,mixed>
     */
    public function pluck(string $column, ?string $keyColumn = null): array
    {
        $cols = "`{$column}`" . ($keyColumn ? ", `{$keyColumn}`" : '');
        $sql = "SELECT {$cols} FROM {$this->table}"
            . ($this->wheres ? ' WHERE ' . implode(' AND ', $this->wheres) : '')
            . ($this->orderBy ? " {$this->orderBy}" : '')
            . ($this->limit !== null ? " LIMIT {$this->limit}" : '')
            . ($this->offset !== null ? " OFFSET {$this->offset}" : '');

        $rows = Model::db()
            ->query($sql, $this->params, true)
            ->results();

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
     * Does any record meet the conditions?
     *
     * @return bool
     */
    public function exists(): bool
    {
        return $this->count() > 0;
    }

    /**
     * Paginate the query.
     *
     * @param int $perPage
     * @param int $page
     * @return array
     */
    public function paginate(int $perPage, int $page = 1): array
    {
        $total = $this->count();
        $lastPage = (int)ceil($total / $perPage);
        $data = $this->limit($perPage)
            ->offset(($page - 1) * $perPage)
            ->get();

        $baseParams = $_GET;
        unset($baseParams['p'], $baseParams['route']);

        $buildUrl = function (int $p) use ($baseParams): string {
            $query = '?p=' . urlencode((string)$p);
            if (!empty($baseParams)) {
                foreach ($baseParams as $k => $v) {
                    $query .= '&' . $k . '=' . urlencode((string)$v);
                }
                $query .= '&';
            }
            return $query;
        };

        $links = array_map(function (int $i) use ($buildUrl, $page) {
            return [
                'url' => $buildUrl($i),
                'label' => (string)$i,
                'active' => $i === $page,
            ];
        }, range(1, $lastPage));

        $pages = [
            'first' => $buildUrl(1),
            'prev' => $page > 1 ? $buildUrl($page - 1) : null,
            'next' => $page < $lastPage ? $buildUrl($page + 1) : null,
            'last' => $buildUrl($lastPage),
        ];

        return [
            'active' => $total > $perPage,
            'data' => $data,
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'last_page' => $lastPage,
            'links' => $links,
            'pages' => $pages,
        ];
    }


    /**
     * Return an array of each model’s attributes.
     *
     * @return array<int, array<string,mixed>>
     */
    public function toArray(): array
    {
        return $this->get()->toArray();
    }

    /**
     * Return JSON representation of the model array.
     */
    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_UNESCAPED_UNICODE);
    }
}
