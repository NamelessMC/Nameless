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

    public static function addItemToCollection($collection, $item) {
        if (!isset(self::$_collections[$collection])) {
            self::$_collections[$collection] = new Collection();
        }

        self::$_collections[$collection]->addItem($item);
    }

    public static function getFullCollection($collection) {
        return (isset(self::$_collections[$collection]) ? self::$_collections[$collection]->getAllItems() : []);
    }

    public static function getEnabledCollection($collection) {
        return (isset(self::$_collections[$collection]) ? self::$_collections[$collection]->getEnabledItems() : []);
    }
}

class Collection {

    private array $_items;

    public function __construct() {
        $this->_items = [];
    }

    public function addItem($item): void {
        $this->_items[] = $item;
    }

    public function getEnabledItems(): array {
        $items = [];

        foreach ($this->_items as $item) {
            if ($item->isEnabled()) {
                $items[] = $item;
            }
        }

        uasort($items, static function ($a, $b) {
            return $a->getOrder() - $b->getOrder();
        });

        return $items;
    }

    public function getAllItems(): array {
        $items = $this->_items;
        uasort($items, static function ($a, $b) {
            return $a->getOrder() - $b->getOrder();
        });
        
        return $items;
    }
}
