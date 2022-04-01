<?php
/**
 * Base for hook implementations
 *
 * @package NamelessMC\Events
 * @author Samerton
 * @version 2.0.0-pr13
 * @license MIT
 */

abstract class HookBase {
    /**
     * Ensure a set of parameters has all the required fields
     *
     * @param array $params Array of parameters to check
     * @param array $required_params Array of required parameter keys
     *
     * @return bool Whether $params contains all of $required_params
     */
    protected static function validateParams(array $params, array $required_params): bool {
        if (empty($params)) {
            return false;
        }

        foreach ($required_params as $required) {
            if (empty($params[$required])) {
                return false;
            }
        }

        return true;
    }
}
