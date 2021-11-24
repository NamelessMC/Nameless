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
        $key = $endpoint->getRoute() . '-' . $endpoint->getMethod();

        if (!isset($this->_endpoints[$key])) {
            $this->_endpoints[$key] = $endpoint;
        }
    }

    /**
     * Find an endpoint which matches this request and `execute()` it.
     * 
     * @param string $route Route to find endpoint for.
     * @param string $method HTTP method to find endpoint for.
     * @param Nameless2API $api Instance of api instance to provide the endpoint.
     */
    public function handle(string $route, string $method, Nameless2API $api) {

        $available_methods = [];
        $matched_endpoint = null;

        foreach ($this->getAll() as $endpoint) {
            if ($endpoint->getRoute() == $route) {

                // Save that we actually found an endpoint
                $matched_endpoint = $endpoint;
                // Save the methods that are available for this endpoint
                $available_methods[] = $endpoint->getMethod();

                if ($endpoint->getMethod() == $method) {
                    $endpoint->execute($api);
                    return;
                }

            }
        }

        if ($matched_endpoint !== null) {
            $api->throwError(3, $api->getLanguage()->get('api', 'invalid_api_method'), "The $route endpoint only accepts " . join(', ', $available_methods) . ", $method was used.", 405);
            return;
        }

        $api->throwError(3, $api->getLanguage()->get('api', 'invalid_api_method'), 'If you are seeing this while in a browser, this does not mean your API is not functioning!', 404);
    }
}
