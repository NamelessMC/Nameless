<?php

namespace NamelessMC\Framework\Extend;
use DI\Container;

class Language extends BaseExtender {

    private string $path;

    public function __construct(string $path) {
        $this->path = $path;
    }

    public function extend(Container $container): void {
        $containerKey = "{$this->moduleName}Language";

        $container->set($containerKey, function() {
            return new \Language($this->path);
        });
    }
}