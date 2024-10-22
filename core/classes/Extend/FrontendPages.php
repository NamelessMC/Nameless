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

        /** @var \Cache */
        $cache = $container->get(\Cache::class);

        /** @var \Navigation */
        $frontendNavigation = $container->get('FrontendNavigation');

        foreach ($this->pages as $page) {
            // Remove leading / from path - allows devs to ->register('/')
            $path = ltrim($page['path'], '/');
            $path = "/{$this->moduleName}/{$path}";
            // Remove ending / if it exists
            $path = rtrim($path, '/');

            $pageName = $page['name'];
            $pageFriendlyName = $moduleLanguage->get($page['friendly_name_translation']);

            $cache->setCache('navbar_order');
            $pageOrder = $cache->fetch("{$pageName}_order", fn () => 5);
    
            $cache->setCache('navbar_icons');
            $pageIcon = $cache->fetch("{$pageName}_icon", fn () => '');

            $cache->setCache('nav_location');
            $pageLocation = $cache->fetch("{$pageName}_location", fn () => 1);

            switch ($pageLocation) {
                case 1:
                    // Navbar
                    $frontendNavigation->add($pageName, $pageFriendlyName, \URL::build($path), 'top', null, $pageOrder, $pageIcon);
                    break;
                case 2:
                    // "More" dropdown
                    $frontendNavigation->addItemToDropdown('more_dropdown', $pageName, $pageFriendlyName, \URL::build($path), 'top', null, $pageIcon, $pageOrder);
                    break;
                case 3:
                    // Footer
                    $frontendNavigation->add($pageName, $pageFriendlyName, \URL::build($path), 'footer', null, $pageOrder, $pageIcon);
                    break;
            }    

            $pages->add(
                $this->moduleDisplayName,
                $path,
                $page['handler'],
                $pageFriendlyName,
                $page['allowWidgets'],
                $this->moduleName,
                true,
            );
        }

        /** @var \Smarty */
        $smarty = $container->get(\Smarty::class);

        foreach ($this->templateDirectories as $directory) {
            $smarty->addTemplateDir($directory);
        }
    }

    public function register(string $path, string $name, string $friendlyNameTranslation, string $handler, bool $allowWidgets): FrontendPages {
        $this->pages[] = [
            'path' => $path,
            'name' => $name,
            'friendly_name_translation' => $friendlyNameTranslation,
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