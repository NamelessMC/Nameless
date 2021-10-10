<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Base collection item class
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

    public abstract function getContent(): string;
}
