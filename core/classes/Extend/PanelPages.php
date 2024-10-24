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

        /** @var \User */
        $user = $container->get(\User::class);

        /** @var \Cache */
        $cache = $container->get(\Cache::class);
        $cache->setCache('panel_sidebar');

        /** @var \Navigation */
        $panelNavigation = $container->get('PanelNavigation');

        $moduleSidebarOrder = array_reduce(array_filter($cache->retrieveAll(), function ($item) {
            return str_ends_with($item, '_order');
        }, ARRAY_FILTER_USE_KEY), function ($carry, $item) {
            return $item > $carry ? $item : $carry;
        }, 0) + 1;
        $panelNavigation->add("{$this->moduleName}_divider", mb_strtoupper($this->moduleDisplayName), 'divider', 'top', null, $moduleSidebarOrder);

        $lastSubPageOrder = $moduleSidebarOrder;

        foreach ($this->pages as $page) {
            $path = ltrim($page['path'], '/');
            $path = "/panel/{$path}";
            $path = rtrim($path, '/');

            $order = $lastSubPageOrder + 0.1;
            $lastSubPageOrder = $order;

            $friendlyName = $moduleLanguage->get($page['friendly_name_translation']);

            if ($user->hasPermission($page['permission'])) {
                $pageIcon = "<i class='nav-icon {$page['icon']}'></i>";
                $panelNavigation->add($page['name'], $friendlyName, \URL::build($path), 'top', null, $order, $pageIcon);
            }

            $pages->add(
                $this->moduleDisplayName,
                $path,
                $page['handler'],
                $friendlyName,
                false,
                $this->moduleName,
                true,
            );
        }
    }

    public function register(string $path, string $name, string $friendlyNameTranslation, string $handler, string $permission, string $icon): PanelPages {
        $this->pages[] = [
            'path' => $path,
            'name' => $name,
            'friendly_name_translation' => $friendlyNameTranslation,
            'handler' => $handler,
            'permission' => $permission,
            'icon' => $icon,
        ];

        return $this;
    }
}