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
     * Get all registered transformers
     *
     * @return array All transformers.
     */
    public static function getAllTransformers(): array {
        return self::$_transformers;
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
                        throw new InvalidArgumentException("Endpoint class must contain an 'execute()' method.");
                    }

                    $reflection = new ReflectionMethod($endpoint, 'execute');
                    if ($reflection->getNumberOfParameters() !== (count($vars) + 1)) {
                        throw new InvalidArgumentException("Endpoint's 'execute()' method must take " . (count($vars) + 1) . " arguments.");
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

    /**
     * Determine if an Endpoint matches a route.
     * If it does, return an array of variables to pass to the endpoint.
     *
     * @param EndpointBase $endpoint Endpoint to attempt to match.
     * @param string $route Route to match.
     * @param Nameless2API $api Instance of API instance to provide the endpoint.
     * @return array|false Array of variables to pass to the endpoint, or false if the route does not match.
     */
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

        $i = 0;
        // first, find any variables (e.g. {user}) in the endpoint's route
        // we save them to an array with their index, so we can reference them later
        foreach ($endpoint_parts as $part) {
            if (strpos($part, '{') === 0 && substr($part, -1) === '}') {
                $endpoint_vars[$i] = substr($part, 1, -1);
            }
            $i++;
        }

        $i = 0;
        // now we go over the route and, if each piece is a variable (according to its index), add it to the returned variable array
        // otherwise, if it's not supposed to be a variable, we check if it matches the endpoint's respective route fragment and exit if it doesn't
        foreach ($route_parts as $part) {
            if (array_key_exists($i, $endpoint_vars)) {
                $route_vars[$endpoint_vars[$i]] = $part;
            } else if ($endpoint_parts[$i] !== $part) {
                return false;
            }
            $i++;
        }

        return $route_vars;
    }

    /**
     * Register a transformer for API route binding.
     *
     * @param string $type The name of the transformer. This is used to identify the transformer when binding.
     * @param string $module The name of the module that registered the transformer.
     * @param Closure(Nameless2API, string): mixed $transformer Function which converts the value in the URL to the desired type.
     */
    public static function registerTransformer(string $type, string $module, Closure $transformer): void {
        if (isset(self::$_transformers[$type])) {
            throw new InvalidArgumentException("A transformer with for the type '$type' has already been registered.");
        }

        $reflection = new ReflectionFunction($transformer);
        if ($reflection->getNumberOfParameters() !== 2) {
            throw new InvalidArgumentException('Endpoint variable transformer must take 2 arguments (Nameless2API and the raw variable).');
        }

        // if they've provided a typehint for the first argument, make sure it's taking Nameless2API
        $param = $reflection->getParameters()[0];
        if ($param->getType() instanceof ReflectionNamedType && $param->getType()->getName() !== Nameless2API::class) {
            throw new InvalidArgumentException('Endpoint variable transformer must take Nameless2API as the first argument.');
        }

        self::$_transformers[$type] = [
            'module' => $module,
            'transformer' => $transformer,
        ];
    }

    /**
     * Convert a value through a transformer based on its type. If no transformer is found, the value is returned as-is.
     *
     * @param Nameless2API $api Instance of API to provide the transformer.
     * @param string $type The type to use.
     * @param string $value The value to convert.
     */
    private static function convertValue(Nameless2API $api, string $type, string $value) {
        if (array_key_exists($type, self::$_transformers)) {
            return self::$_transformers[$type]['transformer']($api, $value);
        }

        return $value;
    }
}
