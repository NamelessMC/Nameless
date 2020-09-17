<?php
/*
 *	Made by Aberdeener
 * 
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  API Endpoints class
 */
abstract class EndpointBase {

    protected
        $_route,
        $_module,
        $_enabled = false;

    public function getRoute() {
        return $this->_route;
    }

    public function getModule() {
        return $this->_module;
    }

    public function isEnabled() {
        return $this->_enabled;
    }

    public function setEnabled() {
        $this->_enabled = true;
    }

    public abstract function execute(Nameless2API $api);

}