<?php
/**
 * Endpoint management class.
 *
 * @package NamelessMC\Endpoints
 * @author Aberdeener
 * @version 2.0.0-pr13
 * @license MIT
 */
class Endpoints {

    use MatchesRoutes;
    use ManagesTransformers;

    /** @var EndpointBase[] */
    private iterable $_endpoints = [];

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
            if (($vars = $this->matchRoute($endpoint, $route)) !== false) {
                // Save that we actually found an endpoint
                $matched_endpoint = $endpoint;
                // Save the methods that are available for this endpoint
                $available_methods[] = $endpoint->getMethod();

                if ($endpoint->getMethod() === $method) {
                    if (!$endpoint->isAuthorised($api)) {
                        $api->throwError(36, 'NOT_AUTHORISED', $endpoint->getAuthType(), 403);
                    }

                    if (!method_exists($endpoint, 'execute')) {
                        throw new InvalidArgumentException("Endpoint class must contain an 'execute()' method.");
                    }

                    $reflection = new ReflectionMethod($endpoint, 'execute');
                    if ($reflection->getNumberOfParameters() !== (count($vars) + 1)) {
                        throw new InvalidArgumentException("Endpoint's 'execute()' method must take " . (count($vars) + 1) . " arguments. Endpoint: " . $endpoint->getRoute());
                    }

                    $endpoint->execute(
                        $api,
                        ...array_map(function ($type, $value) use ($api) {
                            return $this::transform($api, $type, $value);
                        }, array_keys($vars), $vars)
                    );
                    return;
                }
            }
        }

        if ($matched_endpoint !== null) {
            $api->throwError(3, $api->getLanguage()->get('api', 'invalid_api_method'), "The $route endpoint only accepts " . implode(', ', $available_methods) . ", $method was used.", 405);
        }

        $api->throwError(3, $api->getLanguage()->get('api', 'invalid_api_method'), 'If you are seeing this while in a browser, this does not mean your API is not functioning!', 404);
    }
}
