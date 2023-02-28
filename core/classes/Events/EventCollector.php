<?php

use DebugBar\DataCollector\AssetProvider;
use DebugBar\DataCollector\DataCollector;
use DebugBar\DataCollector\Renderable;

/**
 * Interacts with the DebugBar to display executed events.
 *
 * @see DebugBarHelper
 * @package NamelessMC\Events
 * @author Aberdeener
 * @version 2.2.0
 * @license MIT
 */
class EventCollector extends DataCollector implements Renderable, AssetProvider {

    private array $_events = [];
    private static EventCollector $_instance;

    public static function getInstance(): EventCollector {
        return self::$_instance ??= new self();
    }

    public function called(string $event, array $params): void {
        $this->_events[] = [
            'event' => $event,
            'params' => $params
        ];
    }

    public function collect(): array {
        $events = [];

        foreach ($this->_events as $i => $event) {
            ++$i;
            $events["{$event['event']} #$i"] = [
                $this->getVarDumper()->renderVar($event['params']),
            ];
        }

        return [
            'count' => count($events),
            'vars' => $events,
        ];
    }

    public function getName(): string {
        return 'events';
    }

    public function getAssets(): array {
        return $this->getVarDumper()->getAssets();
    }

    public function getWidgets(): array
    {
        return [
            'events' => [
                'icon' => 'tags',
                'widget' => 'PhpDebugBar.Widgets.HtmlVariableListWidget',
                'map' => 'events.vars',
                'default' => '{}',
            ],
            'events:badge' => [
                'map' => 'events.count',
                'default' => 0,
            ],
        ];
    }
}
