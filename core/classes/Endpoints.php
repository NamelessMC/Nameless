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

    /** @var EndpointBase[] */
    private iterable $_endpoints = [];

    /**
     * Get all registered Endpoints
     * 
     * @return EndpointBase[] All endpoints.
     */
    public function getAll(): iterable {
        return $this->_endpoints;
    }

    /**
     * Register an endpoint if it's route is not already taken.
     * 
     * @param EndpointBase $endpoint Instance of endpoint class to register.
     */
    public function add(EndpointBase $endpoint): void {
        if (!isset($this->_endpoints[$endpoint->getRoute()])) {
            $this->_endpoints[$endpoint->getRoute()] = $endpoint;
        }
    }

    /**
     * Find an endpoint which matches this request and `execute()` it.
     * 
     * @param string $request Route to find endpoint for.
     * @param Nameless2API $api Instance of api instance to provide the endpoint.
     * @return bool True when endpoint is found and executed, false if not.
     */
    public function handle(string $request, Nameless2API $api): bool {
        foreach ($this->getAll() as $endpoint) {
            if ($endpoint->getRoute() == $request) {
                $endpoint->execute($api);
                return true;
            }
        }

        return false;
    }
}
