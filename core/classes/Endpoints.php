<?php
/*
 *	Made by Aberdeener
 *
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Endpoints class
 */

class Endpoints {

    private $_endpoints = array();

    public function getAll() {
        return $this->_endpoints;
    }

    public function add(EndpointBase $endpoint) {
        if (!isset($this->_endpoints[$endpoint->getRoute()])) {
            $this->_endpoints[$endpoint->getRoute()] = $endpoint;
        }
    }

    public function handle($request, Nameless2API $api) {
        foreach ($this->getAll() as $endpoint) {
            if ($endpoint->getRoute() == $request) {
                $endpoint->execute($api);
                return true;
            }
        }

        return false;
    }
}
