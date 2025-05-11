<?php

declare(strict_types=1);

namespace Database\ORM\Traits\Query;

use Database\ORM\Query;

/**
 * @mixin Query
 */
trait Modifiers
{
    /**
     * Set LIMIT clause.
     */
    public function limit(int $l): static
    {
        $this->limit = $l;

        return $this;
    }

    /**
     * Set OFFSET clause.
     */
    public function offset(int $o): static
    {
        $this->offset = $o;

        return $this;
    }

    /**
     * Set ORDER BY clause.
     */
    public function orderBy(string $col, string $dir = 'ASC'): static
    {
        $this->orderBy = "ORDER BY `{$col}` {$dir}";

        return $this;
    }

    /**
     * Set raw ORDER BY clause.
     */
    public function orderByRaw(string $raw): static
    {
        $this->orderBy = "ORDER BY {$raw}";

        return $this;
    }
}
