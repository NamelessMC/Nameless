<?php

class Instanceable {

    /**
     * Stores instances of classes with their class name as key.
     */
    private static array $instances = [];

    /**
     * Get or make an instance of the class this was called on.
     * 
     * @return Instanceable
     */
    final public static function getInstance(): Instanceable {
        return self::$instances[static::class] ??= new static();
    }

}
