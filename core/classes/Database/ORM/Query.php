<?php

declare(strict_types=1);

namespace Database\ORM;

use Database\ORM\Traits\Query\Aggregates;
use Database\ORM\Traits\Query\Crud;
use Database\ORM\Traits\Query\Debuggable;
use Database\ORM\Traits\Query\Modifiers;
use Database\ORM\Traits\Query\Operations;
use Database\ORM\Traits\Query\Relations;
use Database\ORM\Traits\Query\Wheres;
use Model;

/**
 * Builds & executes queries, тепер з підтримкою nested ->with().
 *
 * @template TModel of Model
 */
class Query
{
    use Debuggable;
    use Relations;
    use Wheres;
    use Modifiers;
    use Aggregates;
    use Crud;
    use Operations;

    protected string $table;
    protected string $primaryKey;
    /** @var class-string<TModel> */
    protected string $modelClass;

    /**
     * relationName => [ nested, relations ].
     * @var array<string,string[]>
     */
    protected array $with = [];

    protected array $wheres = [];
    protected array $params = [];
    protected ?string $orderBy = null;
    protected ?int $limit = null;
    protected ?int $offset = null;

    /**
     * @param string               $table
     * @param string               $primaryKey
     * @param class-string<TModel> $modelClass
     */
    public function __construct(string $table, string $primaryKey, string $modelClass)
    {
        $this->table = $table;
        $this->primaryKey = $primaryKey;
        $this->modelClass = $modelClass;
    }

    /**
     * Compile the SELECT SQL.
     */
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
        if ($this->offset !== null) {
            $sql .= " OFFSET {$this->offset}";
        }

        return $sql;
    }
}
