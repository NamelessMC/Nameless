<?php
/**
 * Base Collection class
 *
 * @package NamelessMC\Collections
 * @author Samerton
 * @version 2.0.0-pr13
 * @license MIT
 */
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
