<?php

namespace NamelessMC\Framework\Extend;

use DI\Container;

class Sitemap extends BaseExtender {

    private $paths = [];

    public function extend(Container $container): void {
        /** @var \Pages */
        $pages = $container->get(\Pages::class);

        foreach ($this->paths as $path) {
            $pages->registerSitemapMethod(static function (\SitemapPHP\Sitemap $sitemap) use ($path) {
                $sitemap->addItem(\URL::build($path['path']), $path['priority']);
            });
        }
    }

    public function path(string $path, float $priority = \SitemapPHP\Sitemap::DEFAULT_PRIORITY): self {
        $this->paths[] = [
            'path' => $path,
            'priority' => $priority
        ];

        return $this;
    }
}