<?php

namespace NamelessMC\Framework\Extend;

class ModuleLifecycle extends BaseExtender
{
    private array $onInstall;
    private array $onEnable;
    private array $onDisable;

    public function extend(\DI\Container $container): void {
        $this->module->setOnInstall($this->onInstall);
        $this->module->setOnEnable($this->onEnable);
        $this->module->setOnDisable($this->onDisable);
    }

    public function onInstall(string $hook): ModuleLifecycle {
        $this->onInstall[] = $hook;

        return $this;
    }

    public function onEnable(string $hook): ModuleLifecycle {
        $this->onEnable[] = $hook;

        return $this;
    }

    public function onDisable(string $hook): ModuleLifecycle {
        $this->onDisable[] = $hook;

        return $this;
    }
}