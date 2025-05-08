<?php


/**
 * Marker interface for custom attribute casters.
 */
interface Castable
{
    /**
     * Transform the raw DB value into whatever PHP type you need.
     *
     * @param mixed $value
     * @return mixed
     */
    public function cast(mixed $value): mixed;
}