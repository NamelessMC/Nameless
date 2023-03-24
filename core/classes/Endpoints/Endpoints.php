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
                        $api->throwError(
                            $endpoint->getAuthType() === EndpointBase::AUTH_TYPE_API_KEY
                                ? Nameless2API::ERROR_INVALID_API_KEY
                                : Nameless2API::ERROR_NOT_AUTHORIZED,
                            null,
                            403
                        );
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
            $api->throwError(Nameless2API::ERROR_INVALID_API_METHOD, "The $route endpoint only accepts " . implode(', ', $available_methods) . ", $method was used.", 405);
        }

        $api->throwError(Nameless2API::ERROR_INVALID_API_METHOD, 'If you are seeing this while in a browser, this means your API is functioning!', 404);
    }

    /**
     * Recursively scan, preload and register EndpointBase classes in a folder.
     *
     * @see EndpointBase
     *
     * @param string $path Path to scan from.
     */
    public function loadEndpoints(string $path): void {
        $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS));

        foreach ($rii as $file) {
            if ($file->isDir()) {
                $this->loadEndpoints($file);
                return;
            }

            if ($file->getFilename() === '.DS_Store') {
                continue;
            }

            require_once($file->getPathName());

            $endpoint_class_name = str_replace('.php', '', $file->getFilename());

            try {
                /** @var EndpointBase $endpoint */
                $endpoint = new $endpoint_class_name();

                $key = $endpoint->getRoute() . '-' . $endpoint->getMethod();

                if (!isset($this->_endpoints[$key])) {
                    $this->_endpoints[$key] = $endpoint;
                }
            } catch (Error $error) {
                // Silently ignore errors caused by invalid endpoint files,
                // but make a log entry for debugging purposes.
                ErrorHandler::logCustomError($error->getMessage());
            }
        }
    }
}
