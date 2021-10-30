<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0
 *
 *  Event handler class
 */

class EventHandler {

    private static array $_events = [];
    private static array $_webhooks = [];

    /**
     * Register an event.
     * 
     * @param string $event Name of event to add.
     * @param string $description Human readable description.
     * @param array|null $params Array of available parameters and their descriptions.
     */
    public static function registerEvent(string $event, string $description, array $params = []): void {
        self::$_events[$event] = [
            'description' => $description,
            'params' => $params,
            'listeners' => [],
        ];
    }

    /**
     * Register hooks.
     * 
     * @param array $webhooks Array of webhooks to register
     */
    public static function registerWebhooks(array $webhooks): void {
        self::$_webhooks = $webhooks;
    }

    /**
     * Register an event listener for a module.
     * 
     * @param string $event Event name to hook into (must be registered with `registerEvent()`).
     * @param string $listener Listener function name to execute.
     */
    public static function registerListener(string $event, string $listener):  void {
        if (!isset(self::$_events[$event])) {
            self::registerEvent($event, $event); // Silently create event if it doesnt exist, maybe throw exception instead?
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

        if (!isset($params['event'])) {
            $params['event'] = $event;
        }

        // Execute module listeners
        if (isset(self::$_events[$event]['listeners'])) {
            foreach (self::$_events[$event]['listeners'] as $listener) {
                call_user_func($listener, $params);
            }
        }

        // Execute user made Discord webhooks
        foreach (self::$_webhooks as $webhook) {
            if (in_array($event, $webhook['events'])) {
                if (isset($params['available_hooks'])) {
                    if (in_array($webhook['id'], $params['available_hooks'])) {
                        $params['webhook'] = $webhook['url'];
                        call_user_func($webhook['action'], $params);
                    }
                } else {
                    $params['webhook'] = $webhook['url'];
                    call_user_func($webhook['action'], $params);
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
        $return = [];

        foreach (self::$_events as $name => $meta) {
            $return[$name] = $meta['description'];
        }

        return $return;
    }
}
