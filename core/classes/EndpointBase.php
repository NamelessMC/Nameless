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
              $_description,
              $_method;
    
    /**
     * Get route of this Endpoint.
     *
     * @return string Endpoint's route.
     */
    public function getRoute() {
        return $this->_route;
    }

    /**
     * Get name of module of this Endpoint.
     *
     * @return string Endpoint's modules name.
     */
    public function getModule() {
        return $this->_module;
    }

    /**
     * Get description of this Endpoint.
     *
     * @return string Endpoint's description.
     */
    public function getDescription() {
        return $this->_description;
    }

    /**
     * Get method of this Endpoint (either `POST` or `GET`).
     *
     * @return string Endpoint's method.
     */
    public function getMethod() {
        return $this->_method;
    }

    /**
     * Execute this Endpoint.
     * 
     * @param Nameless2API $api Instance of API class to use.
     */
    public abstract function execute(Nameless2API $api);

}
