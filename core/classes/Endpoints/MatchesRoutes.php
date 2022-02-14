<?php
/**
 * Contains methods for matching API requests with endpoint routes.
 *
 * @package NamelessMC\Endpoints
 * @author Aberdeener
 * @version 2.0.0-pr13
 * @license MIT
 */
trait MatchesRoutes {

    /**
     * Determine if an Endpoint matches a route.
     * If it does, return an array of variables to pass to the endpoint.
     *
     * @param EndpointBase $endpoint Endpoint to attempt to match.
     * @param string $route Route to match.
     * @return array|false Array of variables to pass to the endpoint, or false if the route does not match.
     */
    private function matchRoute(EndpointBase $endpoint, string $route) {
        $endpoint_parts = explode('/', $endpoint->getRoute());
        $endpoint_vars = [];

        $route_parts = explode('/', $route);
        $route_vars = [];

        // first, find any variables (e.g. {user}) in the endpoint's route
        // we save them to an array with their index, so we can reference them later
        foreach ($endpoint_parts as $i => $part) {
            if ($this->isVariable($part)) {
                $endpoint_vars[$i] = $this->stripVariable($part);
            }
        }

        if (count($endpoint_parts) !== count($route_parts)) {
            return false;
        }

        // now we go over the route and, if each piece is a variable (according to its index), add it to the returned variable array
        // otherwise, if it's not supposed to be a variable, we check if it matches the endpoint's respective route fragment and exit if it doesn't
        foreach ($route_parts as $i => $part) {
            if (array_key_exists($i, $endpoint_vars)) {
                $route_vars[$endpoint_vars[$i]] = $part;
            } else if ($endpoint_parts[$i] !== $part) {
                return false;
            }
        }

        return $route_vars;
    }

    private function isVariable(string $type) : bool {
        return str_starts_with($type, '{') && str_ends_with($type, '}');
    }

    private function stripVariable(string $type) : string {
        return substr($type, 1, -1);
    }
}
