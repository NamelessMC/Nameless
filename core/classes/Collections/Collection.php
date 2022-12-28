<?php
declare(strict_types=1);

/**
 * Base Collection class
 *
 * @package NamelessMC\Collections
 * @author Samerton
 * @version 2.0.0-pr13
 * @license MIT
 */
class Collection {

    /**
     * @var CollectionItemBase[]
     */
    private array $_items;

    /**
     * @var CollectionItemBase[]
     */
    private array $_enabled_items;

    public function __construct() {
        $this->_items = [];
        $this->_enabled_items = [];
    }

    /**
     * @param CollectionItemBase $item
     * @return void
     */
    public function addItem(CollectionItemBase $item): void {
        $this->_items[] = $item;
        if ($item->isEnabled()) {
            $this->_enabled_items[] = $item;
        }
    }

    /**
     * @return CollectionItemBase[]
     */
    public function getEnabledItems(): array {
        uasort($this->_enabled_items, static function (CollectionItemBase $a, CollectionItemBase $b) {
            return $a->getOrder() - $b->getOrder();
        });

        return $this->_enabled_items;
    }

    /**
     * @return CollectionItemBase[]
     */
    public function getAllItems(): array {
        uasort($this->_items, static function (CollectionItemBase $a, CollectionItemBase $b) {
            return $a->getOrder() - $b->getOrder();
        });

        return $this->_items;
    }
}
