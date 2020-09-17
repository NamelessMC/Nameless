<?php 

class Endpoints {

    private 
        $_endpoints = array();

    public function __construct() {}

    public function getAll() {
        return $this->_endpoints;
    }

    public function add(EndpointBase $endpoint) {
        if (!isset($this->_endpoints[$endpoint->getRoute()])) {
            $this->_endpoints[$endpoint->getRoute()] = $endpoint;
        }
    }

    public function handle($request, $api) {
        foreach ($this->_endpoints as $endpoint) {
            if ($endpoint->getRoute() == $request) {
                $endpoint->execute($api);
            }
        }
        return false;
    }
}