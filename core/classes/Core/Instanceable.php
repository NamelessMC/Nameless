<?php

class Instanceable {

    /**
     * Stores instances of classes with their class name as key.
     */
    private static array $_instances = [];

    /**
     * Get or make an instance of the class this was called on.
     *
     * @return static
     */
    final public static function getInstance() {
        return self::$_instances[static::class] ??= new static();
    }

}
