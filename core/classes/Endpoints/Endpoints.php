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
     * @var array Mapping of key names to closures to transform a variable into an object (ie, a user ID to a User object)
     */
    private static array $_transformers = [];

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
     * Get all registered Endpoints
     *
     * @return EndpointBase[] All endpoints.
     */
    public function getAll(): iterable {
        return $this->_endpoints;
    }

    /**
     * Find an endpoint which matches this request and `execute()` it.
     *
     * @param string $route Route to find endpoint for.
     * @param string $method HTTP method to find endpoint for.
     * @param Nameless2API $api Instance of api instance to provide the endpoint.
     */
    public function handle(string $route, string $method, Nameless2API $api): void {
        $available_methods = [];
        $matched_endpoint = null;

        foreach ($this->getAll() as $endpoint) {
            if (($vars = $this->matchRoute($endpoint, $route, $api)) !== false) {
                // Save that we actually found an endpoint
                $matched_endpoint = $endpoint;
                // Save the methods that are available for this endpoint
                $available_methods[] = $endpoint->getMethod();

                if ($endpoint->getMethod() === $method) {
                    if (!method_exists($endpoint, 'execute')) {
                        $api->throwError(35, $api->getLanguage()->get('api', ''));
                        die();
                    }

                    $endpoint->execute(
                        $api,
                        ...array_map(function ($type, $value) use ($api) {
                            return $this::convertValue($api, $type, $value);
                        }, array_keys($vars), $vars)
                    );
                    return;
                }
            }
        }

        if ($matched_endpoint !== null) {
            $api->throwError(3, $api->getLanguage()->get('api', 'invalid_api_method'), "The $route endpoint only accepts " . implode(', ', $available_methods) . ", $method was used.", 405);
            return;
        }

        $api->throwError(3, $api->getLanguage()->get('api', 'invalid_api_method'), 'If you are seeing this while in a browser, this does not mean your API is not functioning!', 404);
    }

    private function matchRoute(EndpointBase $endpoint, string $route, Nameless2API $api) {
        if (in_array($route, $endpoint->getRouteAliases(), true)) {
            $error = str_replace(['{x}', '{y}'], [$route, $endpoint->getRoute()], $api->getLanguage()->get('api', 'route_alias_used'));

            Log::getInstance()->log(Log::Action('api/route_alias_used'), $error);

            $api->throwError(3, $api->getLanguage()->get('api', 'route_alias_used'), $error);
            die();
        }

        $endpoint_parts = explode('/', $endpoint->getRoute());
        $endpoint_vars = [];

        $route_parts = explode('/', $route);
        $route_vars = [];

        $idx = 0;
        // first, find any variables (e.g. {user}) in the endpoint's route
        // we save them to an array with their index so we can reference them later
        foreach ($endpoint_parts as $part) {
            if (strpos($part, '{') === 0 && substr($part, -1) === '}') {
                $endpoint_vars[$idx] = substr($part, 1, -1);
            }
            $idx++;
        }

        $idx = 0;
        // now we go over the route and, if each piece is a variable (according to its index), add it to the returned variable array
        // otherwise, if it's not supposed to be a variable, we check if it matches the endpoint's respective route fragment and exit if it doesn't
        foreach ($route_parts as $part) {
            if (array_key_exists($idx, $endpoint_vars)) {
                $route_vars[$endpoint_vars[$idx]] = $part;
            } else if ($endpoint_parts[$idx] !== $part) {
                return false;
            }
            $idx++;
        }

        return $route_vars;
    }

    public static function registerTransformer(string $type, Closure $transformer): void {
        $reflection = new ReflectionFunction($transformer);
        if ($reflection->getNumberOfParameters() !== 2) {
            throw new InvalidArgumentException('Endpoint variable transformer must take 2 arguments (Nameless2API and the raw variable).');
        }

        self::$_transformers[$type] = $transformer;
    }

    private static function convertValue(Nameless2API $api, string $type, string $value) {
        if (array_key_exists($type, self::$_transformers)) {
            return self::$_transformers[$type]($api, $value);
        }

        return $value;
    }
}
