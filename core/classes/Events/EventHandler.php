<?php
/**
 * Handles registering and triggering events.
 *
 * @package NamelessMC\Events
 * @author Samerton
 * @author Aberdeener
 * @version 2.0.0-pr13
 * @license MIT
 */
class EventHandler {

    private static array $_events = [];
    private static array $_webhooks = [];

    /**
     * Register webhooks.
     *
     * @param array $webhooks Array of webhooks to register
     */
    public static function registerWebhooks(array $webhooks): void {
        self::$_webhooks = $webhooks;
    }

    /**
     * Register an event.
     * This must be called in the module's constructor.
     *
     * @param string $event Name of event to add.
     * @param string $description Human readable description.
     * @param array $params Array of available parameters and their descriptions.
     * @param bool $return Whether to return $params afterwards
     * @param bool $internal Whether to hide this hook from users in the StaffCP (ie for internal events)
     */
    public static function registerEvent(
        string $event,
        string $description,
        array $params = [],
        bool $return = false,
        bool $internal = false
    ): void {
        // Don't re-register if the event already exists, just update the params and description.
        // This is to "fix" when registerListener is called for an event that has not been registered yet.
        if (isset(self::$_events[$event])) {
            self::$_events[$event]['description'] = $description;
            self::$_events[$event]['internal'] = $internal;

            self::$_events[$event]['params'] = array_merge(
                self::$_events[$event]['params'],
                $params
            );

            self::$_events[$event]['shouldReturn'] = $return;

            return;
        }

        self::$_events[$event] = [
            'description' => $description,
            'internal' => $internal,
            'params' => $params,
            'shouldReturn' => $return,
            'listeners' => [],
        ];
    }

    /**
     * Register an event listener for a module.
     * This must be called in the module's constructor.
     *
     * @param string $event Event name to hook into (must be registered with `registerEvent()`).
     * @param callable $callback Listener callback to execute.
     * @param bool $advanced When true, the callback will be given specific parameters as per its method declaration, otherwise it will be given the raw $params array.
     * @param int $priority Execution priority - higher gets executed first
     */
    public static function registerListener(string $event, callable $callback, bool $advanced = false, int $priority = 10): void {
        if (!isset(self::$_events[$event])) {
            // Silently create event if it doesn't exist, maybe throw exception instead?
            self::registerEvent($event, $event);
        }

        self::$_events[$event]['listeners'][] = [
            'callback' => $callback,
            'advanced' => $advanced,
            'priority' => $priority,
        ];
    }

    /**
     * Execute an event.
     *
     * @param string $event Event name to call.
     * @param array $params Params to pass to the event's function.
     *
     * @return array|null Response of hook, can be any type or null when event does not exist
     */
    public static function executeEvent(string $event, array $params = []) {
        if (!isset(self::$_events[$event])) {
            return null;
        }

        if (!isset($params['event'])) {
            $params['event'] = $event;
        }

        // Execute module listeners
        if (isset(self::$_events[$event]['listeners'])) {
            $listeners = self::$_events[$event]['listeners'];

            usort($listeners, static function($a, $b) {
                return $b['priority'] <=> $a['priority'];
            });

            foreach ($listeners as $listener) {
                $callback = $listener['callback'];

                if ($listener['advanced'] === false) {
                    $response = $callback($params);
                } else {
                    $toPass = [];

                    foreach ((new ReflectionMethod($callback))->getParameters() as $callbackParam) {
                        if (!isset($params[$callbackParam->getName()])) {
                            throw new InvalidArgumentException(
                                "Callable parameter: {$callbackParam->getName()}, is not set in event params (event: $event)"
                            );
                        }

                        if ($callbackParam->getType() != null) {
                            $eventParamType = get_debug_type($params[$callbackParam->getName()]);

                            if ($callbackParam->getType()->getName() != $eventParamType) {
                                throw new InvalidArgumentException(
                                    "{$callbackParam->getName()} expected to be {$callbackParam->getType()->getName()}, but found: $eventParamType"
                                );
                            }

                            if (!$callbackParam->getType()->isBuiltin()) {
                                throw new InvalidArgumentException(
                                    "Callable parameter must be a built-in type, parameter {$callbackParam->getName()} expects: {$callbackParam->getType()->getName()}"
                                );
                            }
                        }

                        $toPass[] = $params[$callbackParam->getName()];
                    }

                    $response = $callback(...$toPass);
                }

                if (self::$_events[$event]['shouldReturn'] && $response) {
                    $params = $response;
                }
            }
        }

        // Execute user made Discord webhooks
        foreach (self::$_webhooks as $webhook) {
            if (in_array($event, $webhook['events'])) {
                // Since forum events are specific to certain hooks, we need to
                // check that this hook is enabled for the event.
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

        return $params;
    }

    /**
     * Get a list of events.
     *
     * @param bool $internal Whether to include internal events or not
     *
     * @return array List of all currently registered events.
     */
    public static function getEvents(bool $internal = false): array {
        $return = [];

        foreach (self::$_events as $name => $meta) {
            if (!$meta['internal'] || $internal) {
                $return[$name] = $meta['description'];
            }
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
