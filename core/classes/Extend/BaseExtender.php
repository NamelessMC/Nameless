<?php

namespace NamelessMC\Framework\Extend;
use DI\Container;

abstract class BaseExtender {

    protected string $moduleName;
    protected string $moduleDisplayName;

    public function setModuleName(string $moduleName, string $moduleDisplayName): BaseExtender {
        $this->moduleName = $moduleName;
        $this->moduleDisplayName = $moduleDisplayName;

        return $this;
    }

    abstract public function extend(Container $container): void;

}