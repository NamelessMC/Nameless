<?php

namespace NamelessMC\Framework\Extend;

use DI\Container;

class PanelPages extends BaseExtender {

    private $pages = [];

    public function extend(Container $container): void {
        /** @var \Language */
        $moduleLanguage = $container->get("{$this->moduleName}Language");

        /** @var \Pages */
        $pages = $container->get(\Pages::class);

        foreach ($this->pages as $page) {
            // Remove loading / from path - allows devs to ->register('/')
            $path = ltrim($page['path'], '/');
            $path = "/panel/{$this->moduleName}/{$path}";
            // Remove ending / if it exists
            $path = rtrim($path, '/');

            $pages->add(
                $this->moduleName,
                $path,
                $page['handler'],
                $moduleLanguage->get($page['name']),
                false,
                true,
            );
        }
    }

    public function register(string $path, string $name, string $handler): PanelPages {
        $this->pages[] = [
            'path' => $path,
            'name' => $name,
            'handler' => $handler,
        ];

        return $this;
    }
}