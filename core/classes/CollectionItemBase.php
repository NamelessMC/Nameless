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

    private $_order, 
            $_enabled;

    public function __construct($order, $enabled) {
        $this->_order = $order;
        $this->_enabled = $enabled;
    }

    public function getOrder() {
        return $this->_order;
    }

    public function isEnabled() {
        return $this->_enabled;
    }

    public abstract function getContent();
}
