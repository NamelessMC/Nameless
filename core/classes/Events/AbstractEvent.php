<?php

abstract class AbstractEvent {

    /**
     * Convert the class name to the event name.
     * Example: UserDeletedEvent -> userDeleted
     *
     * @return string The name of the subclass, without the "Event" suffix
     */
    public static function name(): string {
        return lcfirst(str_replace('Event', '', static::class));
    }

    abstract public static function description(): array;

    public static function return(): bool {
        return false;
    }

    public static function internal(): bool {
        return false;
    }

    final public function params(): array {
        return get_object_vars($this);
    }

    final public static function dispatch(): ?array {
        return EventHandler::executeEvent(
            self::fromArray(func_get_args())
        );
    }

    final public static function fromArray(array $params): AbstractEvent {
        /** @phpstan-ignore-next-line */
        return new static(...$params);
    }
}
