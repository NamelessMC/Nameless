<?php
/**
 * Provides static access to manage and get Collections.
 *
 * @package NamelessMC\Collections
 * @see Collection
 * @author Samerton
 * @version 2.0.0-pr8
 * @license MIT
 */
class CollectionManager {

    /** @var Collection[] */
    private static array $_collections = [];

    public static function addItemToCollection(string $collection, CollectionItemBase $item): void {
        if (!isset(self::$_collections[$collection])) {
            self::$_collections[$collection] = new Collection();
        }

        self::$_collections[$collection]->addItem($item);
    }

    /**
     * @param string $collection
     * @return CollectionItemBase[]
     */
    public static function getFullCollection(string $collection): array {
        return isset(self::$_collections[$collection])
            ? self::$_collections[$collection]->getAllItems()
            : [];
    }

    /**
     * @param string $collection
     * @return CollectionItemBase[]
     */
    public static function getEnabledCollection(string $collection): array {
        return isset(self::$_collections[$collection])
            ? self::$_collections[$collection]->getEnabledItems()
            : [];
    }
}
