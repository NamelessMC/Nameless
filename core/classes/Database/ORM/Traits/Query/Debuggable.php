<?php
declare(strict_types=1);

namespace Database\ORM\Traits\Query;

/**
 * Trait Debuggable
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
}
