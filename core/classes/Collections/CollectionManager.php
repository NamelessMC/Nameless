<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Collection manager class
 */

class CollectionManager {

    /** @var Collection[] */
    private static array $_collections = [];

    public static function addItemToCollection($collection, $item): void {
        if (!isset(self::$_collections[$collection])) {
            self::$_collections[$collection] = new Collection();
        }

        self::$_collections[$collection]->addItem($item);
    }

    public static function getFullCollection($collection): array {
        return isset(self::$_collections[$collection])
                ? self::$_collections[$collection]->getAllItems()
                : [];
    }

    public static function getEnabledCollection($collection): array {
        return isset(self::$_collections[$collection])
                ? self::$_collections[$collection]->getEnabledItems()
                : [];
    }
}
