<?php

declare(strict_types=1);

namespace Database\ORM\Traits\Query;

use Model;
use RuntimeException;

/**
 * Trait Debuggable.
 *
 * Exposes the raw SQL and bound parameters.
 */
trait Debuggable
{
    /**
     * Return the raw SQL (with placeholders).
     */
    public function toSql(): string
    {
        return $this->buildSelect();
    }

    /**
     * Return the array of bound parameters.
     *
     * @return array
     */
    public function getBindings(): array
    {
        return $this->params;
    }

    /**
     * Returns the Explain implementation plan for request.
     *
     * @return object[]
     */
    public function explain(): array
    {
        $sql = 'EXPLAIN ' . $this->toSql();

        try {
            return Model::db()
                ->query($sql, $this->getBindings(), true)
                ->results();
        } catch (\Exception $e) {
            throw new RuntimeException('Explain failed: ' . $e->getMessage(), 0, $e);
        }
    }
}
