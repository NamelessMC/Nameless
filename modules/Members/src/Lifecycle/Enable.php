<?php

namespace NamelessMC\Members\Lifecycle;

use NamelessMC\Framework\ModuleLifecycle\Hook;

class Enable extends Hook
{
    public function execute(): void
    {
        echo "Members module enabled!";
    }
}