<?php
declare(strict_types=1);

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

    /**
     * @var Collection[]
     */
    private static array $_collections = [];

    /**
     * @var string[]
     */
    private static array $enabled_collections = [];

    /**
     * @param string $collection
     * @param CollectionItemBase $item
     * @param bool $enabled
     *
     * @return void
     */
    public static function addItemToCollection(string $collection, CollectionItemBase $item, bool $enabled = false): void {
        if (!isset(self::$_collections[$collection])) {
            self::$_collections[$collection] = new Collection();
        }

        self::$_collections[$collection]->addItem($item);

        if ($enabled) {
            self::$enabled_collections[$collection] = $collection;
        }
    }

    /**
     * @param string $collection
     *
     * @return CollectionItemBase[]
     */
    public static function getFullCollection(string $collection): array {
        return isset(self::$_collections[$collection])
            ? self::$_collections[$collection]->getAllItems()
            : [];
    }

    /**
     * @param string $collection
     *
     * @return CollectionItemBase[]
     */
    public static function getEnabledCollection(string $collection): array {
        if (!isset(self::$enabled_collections[$collection])) {
            return [];
        }

        return isset(self::$_collections[$collection])
            ? self::$_collections[$collection]->getEnabledItems()
            : [];
    }
}