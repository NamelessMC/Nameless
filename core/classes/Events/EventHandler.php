<?php

/**
 * Handles registering and triggering events.
 *
 * @package NamelessMC\Events
 * @author Samerton
 * @author Aberdeener
 * @version 2.1.0
 * @license MIT
 */
class EventHandler
{
    private static array $_events = [];
    private static array $_webhooks = [];

    /**
     * Register webhooks.
     *
     * @param array $webhooks Array of webhooks to register
     */
    public static function registerWebhooks(array $webhooks): void
    {
        self::$_webhooks = $webhooks;
    }

    /**
     * Register an event.
     * This must be called in the module's constructor.
     *
     * @param class-string|string $event       Name of event to add.
     * @param string              $description Human readable description.
     * @param array               $params      Array of available parameters and their descriptions.
     * @param bool                $return      Whether to return $params afterwards
     * @param bool                $internal    Whether to hide this hook from users in the StaffCP (ie for internal events)
     */
    public static function registerEvent(
        string $event,
        string $description = '',
        array $params = [],
        bool $return = false,
        bool $internal = false
    ): void {
        if (class_exists($event) && is_subclass_of($event, AbstractEvent::class)) {
            $class_name = $event;
            $name = $event::name();
            // We lazy load descriptions for class-based events to avoid loading new Language instances unnecessarily
            $description = fn () => $event::description();
            $return = $event::return();
            $internal = $event::internal();
        } else {
            $name = $event;
            if ($description === '') {
                throw new InvalidArgumentException("Description must be provided for non-class based event '$event'");
            }
        }

        // Don't re-register if the event already exists, just update the params
        // and description. This is to "fix" when registerListener is called
        // for an event that has not been registered yet.
        if (isset(self::$_events[$name])) {
            self::$_events[$name] = [
                'description' => $description,
                'internal' => $internal,
                'params' => $params,
                'listeners' => self::$_events[$name]['listeners'],
            ];

            return;
        }

        self::$_events[$name] = [
            'description' => $description,
            'internal' => $internal,
            'params' => $params,
            'return' => $return,
            'listeners' => [],
            'class_name' => $class_name ?? null,
        ];
    }

    /**
     * Register an event listener for a module.
     * This must be called in the module's constructor.
     *
     * @param string                $event    Event name to listen to.
     * @param callable|class-string $callback Listener callback to execute when event is executed. If class name is provided, we will assume there is a static "execute" method on the class.
     * @param int                   $priority Execution priority - higher gets executed first
     */
    public static function registerListener(string $event, $callback, int $priority = 10): void
    {
        $name = class_exists($event) && is_subclass_of($event, AbstractEvent::class)
            ? $event::name()
            : $event;

        if (!isset(self::$_events[$name])) {
            // Silently create event if it doesn't exist, maybe throw exception instead?
            self::registerEvent($event, $event);
        }

        if (is_string($callback) && class_exists($callback)) {
            $callback = [$callback, 'execute'];
        }

        self::$_events[$name]['listeners'][] = [
            'callback' => $callback,
            'priority' => $priority,
        ];
    }

    /**
     * Execute an event.
     *
     * @param  AbstractEvent $event  Event name to call, or instance of event to execute.
     * @param  array                $params Params to pass to the event's function, not required when a class-based event is used.
     * @return array|null           Response of lissteners, can be any type or null
     */
    public static function executeEvent(AbstractEvent $event, array $params = []): AbstractEvent
    {
        $name = $event::name();
        $params = $event->params();
        $event_object = $event;

        if ((defined('DEBUGGING') && DEBUGGING) && class_exists('DebugBar\DebugBar')) {
            EventCollector::getInstance()->called($name, $params);
        }

        // Pass event name to params if it is not already set. This allows listeners
        // which are still using `array $params` to still get the event name.
        if (!isset($params['event'])) {
            $params['event'] = $name;
        }

        // Execute module listeners
        if (isset(self::$_events[$name]['listeners'])) {
            $listeners = self::$_events[$name]['listeners'];

            usort($listeners, static function ($a, $b) {
                return $b['priority'] <=> $a['priority'];
            });

            foreach ($listeners as $listener) {
                $callback = $listener['callback'];

                $response = $callback($event);
                if (self::$_events[$name]['return']) {
                    $params = $response;
                }
            }
        }

        // Execute webhooks
        foreach (self::$_webhooks as $webhook) {
            if (in_array($name, $webhook['events'])) {
                // Since forum events are specific to certain hooks, we
                // need to check that this hook is enabled for the event.
                if (isset($params['available_hooks']) && !in_array($webhook['id'], $params['available_hooks'])) {
                    continue;
                }

                $callback = $webhook['action'];
                // We are more flexible with webhooks, since a single webhook listener
                // is likely going to handle a variety of different events (DiscordHook for example).
                // We don't have a way to add a "webhook" property to an
                // arbitrary event object, so we'll just pass the webhook
                // URL as a second parameter to the callback.
                $callback($event, $webhook['url']);
            }
        }

        return $event;
    }

    /**
     * Get a list of events to display on the StaffCP webhooks page.
     *
     * @return array List of all currently registered events
     */
    public static function getEvents(bool $showInternal = false): array
    {
        $return = [];

        foreach (self::$_events as $name => $meta) {
            if ($meta['internal'] && !$showInternal) {
                continue;
            }

            if (is_callable($meta['description'])) {
                $description = $meta['description']();
            } else {
                $description = $meta['description'];
            }

            $class = $meta['class_name'];
            $return[$name] = [
                'description' => $description,
                'supports_discord' => $class !== null && is_subclass_of($class, DiscordDispatchable::class),
                'supports_normal' => $class !== null && is_subclass_of($class, HasWebhookParams::class),
            ];
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
    public static function getEvent(string $event): array
    {
        if (!isset(self::$_events[$event])) {
            throw new InvalidArgumentException("Invalid event name: $event");
        }

        return self::$_events[$event];
    }
}
