<?php

/**
 * Handles registering and triggering events.
 *
 * @package NamelessMC\Events
 * @author Samerton
 * @author Aberdeener
 * @version 2.3.0
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
     * @param  class-string<AbstractEvent> $event Event to add.
     * @throws Exception
     */
    public static function registerEvent(string $event): void
    {
        if (!(class_exists($event) && is_subclass_of($event, AbstractEvent::class))) {
            throw new  Exception('Event param must be a class string of type AbstractEvent');
        }

        $name = $event::name();
        // We lazy load descriptions for class-based events to avoid loading new Language instances unnecessarily
        $description = fn () => $event::description();
        $internal = $event::internal();

        // Don't re-register if the event already exists, just update the params
        // and description. This is to "fix" when registerListener is called
        // for an event that has not been registered yet.
        if (isset(self::$_events[$name])) {
            self::$_events[$name] = [
                'description' => $description,
                'internal' => $internal,
                'listeners' => self::$_events[$name]['listeners'],
            ];

            return;
        }

        self::$_events[$name] = [
            'description' => $description,
            'internal' => $internal,
            'listeners' => [],
            'class_name' => $event,
        ];
    }

    /**
     * Register an event listener for a module.
     * This must be called in the module's constructor.
     *
     * @param  class-string<AbstractEvent> $event    Event to listen to.
     * @param  callable|class-string       $callback Listener callback to execute when event is executed. If class name is provided, we will assume there is a static "execute" method on the class.
     * @param  int                         $priority Execution priority - higher gets executed first
     * @throws Exception
     */
    public static function registerListener(string $event, $callback, int $priority = 10): void
    {
        if (!(class_exists($event) && is_subclass_of($event, AbstractEvent::class))) {
            throw new  Exception('Event param must be a class string of type AbstractEvent');
        }

        $name = $event::name();
        if (!isset(self::$_events[$name])) {
            // Silently create event if it doesn't exist, maybe throw exception instead?
            self::registerEvent($event);
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
     * @template T of AbstractEvent
     * @param  T $event
     * @return T
     */
    public static function executeEvent(AbstractEvent $event): AbstractEvent
    {
        $name = $event::name();

        if ((defined('DEBUGGING') && DEBUGGING) && class_exists('DebugBar\DebugBar')) {
            EventCollector::getInstance()->called($name, $event->params());
        }

        // Execute module listeners
        if (isset(self::$_events[$name]['listeners'])) {
            $listeners = self::$_events[$name]['listeners'];

            usort($listeners, static function ($a, $b) {
                return $b['priority'] <=> $a['priority'];
            });

            foreach ($listeners as $listener) {
                $callback = $listener['callback'];
                $callback($event);
            }
        }

        // Execute webhooks
        foreach (self::$_webhooks as $webhook) {
            if (in_array($name, $webhook['events'])) {
                // Since forum events are specific to certain hooks, we
                // need to check that this hook is enabled for the event.
                if (isset($event->available_hooks) && !in_array($webhook['id'], $event->available_hooks)) {
                    continue;
                }

                $callback = $webhook['action'];
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
