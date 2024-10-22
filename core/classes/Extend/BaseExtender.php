<?php

namespace NamelessMC\Framework\Extend;
use DI\Container;

abstract class BaseExtender {

    protected \ComposerModuleWrapper $module;
    protected string $moduleName;
    protected string $moduleDisplayName;

    public function setModule(\ComposerModuleWrapper $module): BaseExtender {
        $this->module = $module;

        $this->moduleName = $module->getPrivateName();
        $this->moduleDisplayName = $module->getName();

        return $this;
    }

    abstract public function extend(Container $container): void;

}