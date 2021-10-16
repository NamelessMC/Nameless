<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0
 *
 *  Hook handler class
 */

class HookHandler {

    private static array $_events = array();
    private static array $_hooks = array();

    /**
     * Register an event name.
     * 
     * @param string $event Name of event to add.
     * @param string $description Human readable description.
     * @param array|null $params Array of available parameters and their descriptions.
     */
    public static function registerEvent(string $event, string $description, array $params = array()): void {
        if (!isset(self::$_events[$event])) {
            self::$_events[$event] = array();
        }

        self::$_events[$event]['description'] = $description;
        self::$_events[$event]['params'] = $params;
    }

    /**
     * Register hooks.
     * 
     * @param array $hooks Array of webhooks to register
     */
    public static function registerWebhooks(array $hooks): void {
        self::$_hooks = $hooks;
    }

    /**
     * Register an event listener for a module.
     * 
     * @param string $event Event name to hook into (must be registered with `registerEvent()`).
     * @param string $listener Listener function name to execute.
     */
    public static function registerListener(string $event, string $listener):  void {
        if (!isset(self::$_events[$event])) {
            self::$_events[$event] = array();
        }

        self::$_events[$event]['listeners'][] = $listener;
    }

    /**
     * Execute an event.
     * 
     * @param string $event Event name to call.
     * @param array $params Params to pass to the event's function.
     */
    public static function executeEvent(string $event, array $params = []): void {
        if (!isset(self::$_events[$event])) {
            return;
        }

        if (!is_array($params)) {
            $params = array();
        }

        if (!isset($params['event'])) {
            $params['event'] = $event;
        }

        // Execute module hooks
        if (isset(self::$_events[$event]['listeners'])) {
            foreach (self::$_events[$event]['listeners'] as $listener) {
                call_user_func($listener, $params);
            }
        }

        // Execute user made Discord webhooks
        foreach (self::$_hooks as $hook) {
            if (in_array($event, $hook['events'])) {
                if (isset($params['available_hooks'])) {
                    if (in_array($hook['id'], $params['available_hooks'])) {
                        $params['webhook'] = $hook['url'];
                        call_user_func($hook['action'], $params);
                    }
                } else {
                    $params['webhook'] = $hook['url'];
                    call_user_func($hook['action'], $params);
                }
            }
        }
    }

    /**
     * Get a list of events.
     * 
     * @return array List of all currently registered events.
     */
    public static function getEvents(): array {
        $return = array();

        foreach (self::$_events as $name => $meta) {
            $return[$name] = $meta['description'];
        }

        return $return;
    }

    /**
     * Get a certain hook.
     * Not used internally - for modules instead.
     * 
     * @param string $hook Name of hook to find
     * @return array|null Hook with name, null if one does not exist.
     */
    public static function getHook(string $hook): ?array {
        if (isset(self::$_events[$hook])) {
            return self::$_events[$hook];
        }
        
        return null;
    }

    /**
     * Get array of parameters for a specific registered event.
     * Not used internally - for modules instead.
     * 
     * @param string $event Name of event to find.
     * @return array|null Array of params or null if event under name doesnt exist.
     */
    public static function getParameters(string $event): ?array {
        if (isset(self::$_events[$event]['parameters'])) {
            return self::$_events[$event]['parameters'];
        }

        return null;
    }
}
