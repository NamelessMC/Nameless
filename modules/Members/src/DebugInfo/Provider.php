<?php

namespace NamelessMC\Members\DebugInfo;

use NamelessMC\Framework\Debugging\DebugInfoProvider;

class Provider extends DebugInfoProvider {

    public function provide(): array {
        return [
            'foo' => 'bar'
        ];
    }
}