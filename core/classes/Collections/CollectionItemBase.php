<?php
/**
 * Represents a single item within a Collection.
 *
 * @package NamelessMC\Collections
 * @see Collection
 * @author Samerton
 * @version 2.0.0-pr8
 * @license MIT
 */
abstract class CollectionItemBase {

    private int $_order;
    private bool $_enabled;

    public function __construct(int $order, bool $enabled) {
        $this->_order = $order;
        $this->_enabled = $enabled;
    }

    public function getOrder(): int {
        return $this->_order;
    }

    public function isEnabled(): bool {
        return $this->_enabled;
    }

    abstract public function getContent(): string;
}
