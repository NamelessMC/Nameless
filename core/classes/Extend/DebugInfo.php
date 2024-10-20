<?php

namespace NamelessMC\Framework\Extend;

use DI\Container;

class DebugInfo extends BaseExtender {

    public function extend(Container $container): void {
        // ...
    }

    public function provide(string $provider): DebugInfo {
        // ...

        return $this;
    }
}