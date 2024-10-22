<?php

namespace NamelessMC\Framework\ModuleLifecycle;

abstract class Hook
{
    abstract public function execute(): void;
}