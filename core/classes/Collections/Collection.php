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

    /** @var CollectionItemBase[] */
    private array $_items;

    public function __construct() {
        $this->_items = [];
    }

    public function addItem(CollectionItemBase $item): void {
        $this->_items[] = $item;
    }

    /**
     * @return CollectionItemBase[]
     */
    public function getEnabledItems(): array {
        $items = [];

        foreach ($this->_items as $item) {
            if ($item->isEnabled()) {
                $items[] = $item;
            }
        }

        uasort($items, static function (CollectionItemBase $a, CollectionItemBase $b) {
            return $a->getOrder() - $b->getOrder();
        });

        return $items;
    }

    /**
     * @return CollectionItemBase[]
     */
    public function getAllItems(): array {
        $items = $this->_items;
        uasort($items, static function (CollectionItemBase $a, CollectionItemBase $b) {
            return $a->getOrder() - $b->getOrder();
        });

        return $items;
    }
}
