<?php

namespace NamelessMC\Framework\Extend;

use DI\Container;

class PanelPages extends BaseExtender {

    private $pages = [];
    private $templateDirectories = [];


    public function extend(Container $container): void {
        /** @var \Language */
        $moduleLanguage = $container->get("{$this->moduleName}Language");

        /** @var \Pages */
        $pages = $container->get(\Pages::class);

        /** @var \User */
        $user = $container->get(\User::class);

        /** @var \Cache */
        $cache = $container->get(\Cache::class);
        $cache->setCache('panel_sidebar');

        /** @var \Navigation */
        $panelNavigation = $container->get('PanelNavigation');

        $moduleSidebarOrder = $cache->fetch("{$this->moduleName}_order", fn () => 10);
        $panelNavigation->add("{$this->moduleName}_divider", mb_strtoupper($this->moduleDisplayName), 'divider', 'top', null, $moduleSidebarOrder);

        foreach ($this->pages as $page) {
            // Remove loading / from path - allows devs to ->register('/')
            $path = ltrim($page['path'], '/');
            $path = "/panel/{$this->moduleName}/{$path}";
            // Remove ending / if it exists
            $path = rtrim($path, '/');

            $friendlyName = $moduleLanguage->get($page['friendly_name_translation']);

            if ($user->hasPermission($page['permission'])) {
                $pageIcon = $cache->fetch("{$page['name']}_icon", fn () => '<i class="nav-icon fas fa-cogs"></i>');

                $panelNavigation->add($page['name'], $friendlyName, \URL::build($path), 'top', null, $moduleSidebarOrder + 0.1, $pageIcon);
            }

            $pages->add(
                $this->moduleName,
                $path,
                $page['handler'],
                $friendlyName,
                false,
                true,
            );
        }

        /** @var \Smarty */
        $smarty = $container->get(\Smarty::class);

        foreach ($this->templateDirectories as $directory) {
            $smarty->addTemplateDir($directory);
        }
    }

    public function register(string $path, string $name, string $friendlyNameTranslation, string $handler, string $permission): PanelPages {
        $this->pages[] = [
            'path' => $path,
            'name' => $name,
            'friendly_name_translation' => $friendlyNameTranslation,
            'handler' => $handler,
            'permission' => $permission,
        ];

        return $this;
    }

    public function templateDirectory(string $path): PanelPages {
        $this->templateDirectories[] = $path;

        return $this;
    }
}