<?php

/**
 * Represents a class-based event.
 *
 * @package NamelessMC\Events
 * @author Aberdeener
 * @version 2.3.0
 * @license MIT
 */
abstract class AbstractEvent
{
    /**
     * Convert the class name to the event name.
     * Example: UserDeletedEvent -> userDeleted.
     *
     * @return string The name of the subclass, without the "Event" suffix
     */
    public static function name(): string
    {
        return lcfirst(str_replace('Event', '', static::class));
    }

    /**
     * Get the description of the event.
     *
     * @return string The description of the event
     */
    public static function description(): string
    {
        return static::name();
    }

    /**
     * Determine whether to hide this hook from users in the StaffCP, some events should be private.
     *
     * @return bool Whether to hide this hook from users in the StaffCP
     */
    public static function internal(): bool
    {
        return false;
    }

    /**
     * Get the parameters of the event.
     * Parameters are assumed to be public properties of the event class.
     *
     * @return array The parameters of the event
     */
    final public function params(): array
    {
        return get_object_vars($this);
    }

    /**
     * Create an instance of the event from an array of parameters.
     *
     * @param  array         $params The parameters to pass to the event
     * @return AbstractEvent The event instance
     */
    final public static function fromArray(array $params): AbstractEvent
    {
        /** @phpstan-ignore-next-line */
        return new static(...$params);
    }
}
