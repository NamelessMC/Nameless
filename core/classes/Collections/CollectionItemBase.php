<?php
declare(strict_types=1);

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

    /**
     * @param int $order
     * @param bool $enabled
     */
    public function __construct(int $order, bool $enabled) {
        $this->_order = $order;
        $this->_enabled = $enabled;
    }

    /**
     *
     * @return int
     */
    public function getOrder(): int {
        return $this->_order;
    }

    /**
     *
     * @return bool
     */
    public function isEnabled(): bool {
        return $this->_enabled;
    }

    /**
     *
     * @return string
     */
    abstract public function getContent(): string;
}
