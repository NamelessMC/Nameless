<?php
/**
 * Base class endpoints need to extend.
 *
 * @package NamelessMC\Endpoints
 * @author Aberdeener
 * @version 2.0.0-pr13
 * @license MIT
 */
abstract class EndpointBase {

    public const AUTH_TYPE_API_KEY = 'API Key';
    public const AUTH_TYPE_NONE = 'None';
    public const AUTH_TYPE_CUSTOM = 'Custom';

    protected string $_route;
    protected string $_module;
    protected string $_description;
    protected string $_method;

    /**
     * Get route of this Endpoint.
     *
     * @return string Endpoint's route.
     */
    final public function getRoute(): string {
        return $this->_route;
    }

    /**
     * Get name of module of this Endpoint.
     *
     * @return string Endpoint's modules name.
     */
    final public function getModule(): string {
        return $this->_module;
    }

    /**
     * Get description of this Endpoint.
     *
     * @return string Endpoint's description.
     */
    final public function getDescription(): string {
        return $this->_description;
    }

    /**
     * Get method of this Endpoint (either `POST` or `GET`).
     *
     * @return string Endpoint's method.
     */
    final public function getMethod(): string {
        return $this->_method;
    }

    /**
     * Get the authentication type of this Endpoint.
     * Determined by seeing what class it extends.
     * Used to display in the API Endpoints StaffCP page.
     *
     * @return string The auth type.
     */
    final public function getAuthType(): string {
        switch (get_parent_class($this)) {
            case KeyAuthEndpoint::class:
                return self::AUTH_TYPE_API_KEY;
            case NoAuthEndpoint::class:
                return self::AUTH_TYPE_NONE;
            default:
                return self::AUTH_TYPE_CUSTOM;
        }
    }

    /**
     * Determine if this request is authorized to use this Endpoint.
     * Default implementations:
     * - NoAuthEndpoint to return true
     * - KeyAuthEndpoint to return true if the API key in header is valid
     *
     * @param Nameless2API $api Instance of Nameless2API.
     * @return bool
     */
    abstract public function isAuthorised(Nameless2API $api): bool;

}
