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

    protected string $_route;
    protected string $_module;
    protected string $_description;
    protected string $_method;
    
    /**
     * Get route of this Endpoint.
     *
     * @return string Endpoint's route.
     */
    public function getRoute(): string {
        return $this->_route;
    }

    /**
     * Get name of module of this Endpoint.
     *
     * @return string Endpoint's modules name.
     */
    public function getModule(): string {
        return $this->_module;
    }

    /**
     * Get description of this Endpoint.
     *
     * @return string Endpoint's description.
     */
    public function getDescription(): string {
        return $this->_description;
    }

    /**
     * Get method of this Endpoint (either `POST` or `GET`).
     *
     * @return string Endpoint's method.
     */
    public function getMethod(): string {
        return $this->_method;
    }

    /**
     * Execute this Endpoint.
     * 
     * @param Nameless2API $api Instance of API class to use.
     */
    public abstract function execute(Nameless2API $api);

}
