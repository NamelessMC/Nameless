<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  Hook handler class
 */

class HookHandler {

    private static $_events = array();
    private static $_hooks = array();

    /**
     * Register an event name.
     * 
     * @param string $event Name of event to add.
     * @param string $description Human readable description.
     * @param array|null $params Array of available parameters and their descriptions.
     */
    public static function registerEvent($event, $description, $params = array()) {
        if (!isset(self::$_events[$event])) {
            self::$_events[$event] = array();
        }

        self::$_events[$event]['description'] = $description;
        self::$_events[$event]['params'] = $params;
    }

    /**
     * Register hooks.
     * 
     * @param array $hooks Array of hooks to register
     */
    public static function registerHooks($hooks) {
        self::$_hooks = $hooks;
    }

    /**
     * Register an event hook.
     * 
     * @param string $event Event name to hook into (must be registered with `registerEvent()`).
     * @param string $hook Function name to execute.
     */
    public static function registerHook($event, $hook) {
        if (!isset(self::$_events[$event])) {
            self::$_events[$event] = array();
        }

        self::$_events[$event]['hooks'][] = $hook;
    }

    /**
     * Execute an event.
     * 
     * @param string $event Event name to call.
     * @param array|null $params Params to pass to the event's function.
     */
    public static function executeEvent($event, $params = null) {
        if (!isset(self::$_events[$event])) {
            return false;
        }

        if (!is_array($params)) {
            $params = array();
        }

        if (!isset($params['event'])) {
            $params['event'] = $event;
        }

        // Execute system hooks
        if (isset(self::$_events[$event]['hooks'])) {
            foreach (self::$_events[$event]['hooks'] as $hook) {
                call_user_func($hook, $params);
            }
        }

        // Execute user made webhooks
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
     * Get a list of hooks.
     * 
     * @return array List of all currently registered hooks.
     */
    public static function getHooks() {
        $return = array();

        foreach (self::$_events as $key => $item) {
            $return[$key] = $item['description'];
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
    public static function getHook($hook) {
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
    public static function getParameters($event) {
        if (isset(self::$_events[$event]['parameters'])) {
            return self::$_events[$event]['parameters'];
        }

        return null;
    }
}
