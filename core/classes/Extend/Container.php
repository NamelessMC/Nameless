<?php

namespace NamelessMC\Framework\Extend;

use DI\Container as DIContainer;

class Container extends BaseExtender {

    private $singletons = [];

    public function extend(DIContainer $container): void {
        foreach ($this->singletons as $class) {
            $container->set($class, $container->get($class));
        }
    }

    public function singleton(string $class): Container {
        $this->singletons[] = $class;

        return $this;
    }
}