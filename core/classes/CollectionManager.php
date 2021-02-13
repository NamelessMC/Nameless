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

    private static $_collections = array();

    public static function addItemToCollection($collection, $item) {
        if (!isset(self::$_collections[$collection])) {
            self::$_collections[$collection] = new Collection();
        }

        self::$_collections[$collection]->addItem($item);
    }

    public static function getFullCollection($collection) {
        return (isset(self::$_collections[$collection]) ? self::$_collections[$collection]->getAllItems() : array());
    }

    public static function getEnabledCollection($collection) {
        return (isset(self::$_collections[$collection]) ? self::$_collections[$collection]->getEnabledItems() : array());
    }
}

class Collection {

    private $_items;

    public function __construct() {
        $this->_items = array();
    }

    public function addItem($item) {
        $this->_items[] = $item;
    }

    public function getEnabledItems() {
        $items = array();

        foreach ($this->_items as $item) {
            if ($item->isEnabled()) {
                $items[] = $item;
            }
        }

        uasort($items, function ($a, $b) {
            return $a->getOrder() - $b->getOrder();
        });

        return $items;
    }

    public function getAllItems() {
        $items = $this->_items;
        uasort($items, function ($a, $b) {
            return $a->getOrder() - $b->getOrder();
        });
        
        return $items;
    }
}
