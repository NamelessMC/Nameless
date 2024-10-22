<?php

namespace NamelessMC\Framework\Debugging;

abstract class DebugInfoProvider
{
    abstract public function provide(): array;
}