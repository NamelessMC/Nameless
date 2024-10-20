<?php

namespace NamelessMC\Framework\Extend;

use DI\Container;

class FrontendPages extends BaseExtender {

    private $pages = [];
    private $templateDirectories = [];

    public function extend(Container $container): void {
        /** @var \Language */
        $moduleLanguage = $container->get("{$this->moduleName}Language");

        /** @var \Pages */
        $pages = $container->get(\Pages::class);

        foreach ($this->pages as $page) {
            // Remove loading / from path - allows devs to ->register('/')
            $path = ltrim($page['path'], '/');
            $path = "/{$this->moduleName}/{$path}";
            // Remove ending / if it exists
            $path = rtrim($path, '/');

            $pages->add(
                $this->moduleName,
                $path,
                $page['handler'],
                $moduleLanguage->get($page['name']),
                $page['allowWidgets'],
                true,
            );
        }

        /** @var \Smarty */
        $smarty = $container->get(\Smarty::class);

        foreach ($this->templateDirectories as $directory) {
            $smarty->addTemplateDir($directory);
        }
    }

    public function register(string $path, string $name, string $handler, bool $allowWidgets): FrontendPages {
        $this->pages[] = [
            'path' => $path,
            'name' => $name,
            'handler' => $handler,
            'allowWidgets' => $allowWidgets
        ];

        return $this;
    }

    public function templateDirectory(string $path): FrontendPages {
        $this->templateDirectories[] = $path;

        return $this;
    }
}