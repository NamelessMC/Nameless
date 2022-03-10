<?php

/**
 * Allows classes to extend this to make singleton instances easily.
 *
 * @package NamelessMC\Core
 * @author Aberdeener
 * @version 2.0.0-pr13
 * @license MIT
 */
class Instanceable {

    /**
     * Stores instances of classes with their class name as key.
     *
     * @var array<class-string, static>
     */
    private static array $_instances = [];

    /**
     * Get or make an instance of the class this was called on.
     *
     * @return static Instance of the class this was called on.
     */
    final public static function getInstance() {
        /** @phpstan-ignore-next-line  */
        return self::$_instances[static::class] ??= new static();
    }

}
