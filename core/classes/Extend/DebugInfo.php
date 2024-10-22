<?php

namespace NamelessMC\Framework\Extend;

use DI\Container;

class DebugInfo extends BaseExtender {

    private string $provider;

    public function extend(Container $container): void {
        $this->module->setDebugInfoProvider($this->provider);
    }

    public function provide(string $provider): DebugInfo {
        $this->provider = $provider;

        return $this;
    }
}