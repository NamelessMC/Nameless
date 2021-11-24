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
     * Register an event. This must be called in the module's constructor.
     *
     * @param string $event Name of event to add.
     * @param string $description Human readable description.
     * @param array|null $params Array of available parameters and their descriptions.
     */
    public static function registerEvent(string $event, string $description, array $params = []): void {
        // Don't re-register if the event already exists, just update the params and description.
        // This is to "fix" when registerListener is called for an event that has not been registered yet.
        if (isset(self::$_events[$event])) {
            self::$_events[$event]['description'] = $description;

            self::$_events[$event]['params'] = array_merge(
                self::$_events[$event]['params'],
                $params
            );

            return;
        }

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
     * This must be called in the module's constructor.
     *
     * @param string $event Event name to hook into (must be registered with `registerEvent()`).
     * @param callable $listener Listener function name to execute.
     */
    public static function registerListener(string $event, callable $listener):  void {
        if (!isset(self::$_events[$event])) {
            // Silently create event if it doesnt exist, maybe throw exception instead?
            self::registerEvent($event, $event);
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

    /**
     * Get data about an event.
     * Not used internally, currently for WebSend.
     *
     * @param string $event Name of event to get data for.
     * @returns array Event data.
     */
    public static function getEvent(string $event): array {
        if (!isset(self::$_events[$event])) {
            throw new InvalidArgumentException("Invalid event name: $event");
        }

        return self::$_events[$event];
    }
}
