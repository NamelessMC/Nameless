<?php

use DebugBar\DataCollector\AssetProvider;
use DebugBar\DataCollector\DataCollector;
use DebugBar\DataCollector\Renderable;

class CacheCollector extends DataCollector implements Renderable, AssetProvider {

    private array $_cache_calls = [];
    private static CacheCollector $_instance;

    public static function getInstance(): CacheCollector {
        return self::$_instance ??= new self();
    }

    public function recordCheck(string $key, bool $is_cached): void {
        $this->recordEvent('check', [
            'key' => $key,
            'is_cached' => $is_cached,
        ]);
    }

    public function recordHit(string $key, $value): void {
        $this->recordEvent('hit', [
            'key' => $key,
            'value' => $value,
        ]);
    }

    public function recordMiss(string $key): void {
        $this->recordEvent('miss', [
            'key' => $key,
        ]);
    }

    public function recordSet(string $key, $value, int $ttl): void {
        $this->recordEvent('set', [
            'key' => $key,
            'value' => $value,
            'ttl' => $ttl,
        ]);
    }

    public function collect(): array {
        $events = [];

        foreach ($this->_cache_calls as $i => $event) {
            ++$i;
            ['event' => $event, 'params' => $params] = $event;

            $events["{$event} #{$i}"] = [
                $this->getVarDumper()->renderVar($params),
            ];
        }

        return [
            'count' => count($events),
            'vars' => $events,
        ];
    }

    private function recordEvent(string $event, array $params): array {
        return $this->_cache_calls[] = [
            'event' => $event,
            'params' => $params,
        ];
    }

    public function getName(): string {
        return 'cache';
    }

    public function getAssets(): array {
        return $this->getVarDumper()->getAssets();
    }

    public function getWidgets(): array
    {
        return [
            'cache' => [
                'icon' => 'tags',
                'widget' => 'PhpDebugBar.Widgets.HtmlVariableListWidget',
                'map' => 'cache.vars',
                'default' => '{}',
            ],
            'cache:badge' => [
                'map' => 'cache.count',
                'default' => 0,
            ],
        ];
    }
}
