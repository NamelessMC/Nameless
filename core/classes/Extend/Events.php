<?php

namespace NamelessMC\Framework\Extend;

use DI\Container;

class Events extends BaseExtender {

    private $listeners = [];

    public function extend(Container $container): void {
        foreach ($this->listeners as $event => $listeners) {
            foreach ($listeners as $listener) {
                \EventHandler::registerListener($event, $listener, 10, $container);
            }
        }
    }

    public function listen(string $event, string $listener): Events {
        if (!isset($this->listeners[$event])) {
            $this->listeners[$event] = [];
        }

        $this->listeners[$event][] = $listener;

        return $this;
    }
}