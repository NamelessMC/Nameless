<?php

namespace NamelessMC\Framework\Extend;

use DI\Container;

class Queries extends BaseExtender {

    private $pages = [];

    public function extend(Container $container): void {
        /** @var \Pages */
        $pages = $container->get(\Pages::class);

        foreach ($this->pages as $page) {
            $path = ltrim($page['path'], '/');
            $path = "/queries/{$path}";
            $path = rtrim($path, '/');

            $pages->add(
                $this->moduleDisplayName,
                $path,
                $page['handler'],
                '',
                false,
                $this->moduleName,
                true,
            );
        }
    }

    public function register(string $path, string $handler): Queries {
        $this->pages[] = [
            'path' => $path,
            'handler' => $handler,
        ];

        return $this;
    }
}