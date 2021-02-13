<?php
/*
 *	Made by Aberdeener
 *
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  EndpointsBase class
 */

abstract class EndpointBase {

    protected $_route,
              $_module,
              $_description;

    public function getRoute() {
        return $this->_route;
    }

    public function getModule() {
        return $this->_module;
    }

    public function getDescription() {
        return $this->_description;
    }

    public abstract function execute(Nameless2API $api);

}
