<?php

namespace NamelessMC\Framework\Extend;

use DI\Container;

class FrontendAssets extends BaseExtender {

    private $globalJsFiles = [];
    private $jsFiles = [];
    private $globalCssFiles = [];
    private $cssFiles = [];

    // TODO, can't use $container->get('FrontendTemplate') since these would be called before the template is intialized
    public function extend(Container $container): void {
        if ($container->has('FrontendAssets')) {
            $frontendAssets = $container->get('FrontendAssets');
        } else {
            $container->set('FrontendAssets', $frontendAssets = []);
        }

        // merge in global assets
        $frontendAssets['globalJsFiles'] = array_merge($frontendAssets['globalJsFiles'] ?? [], $this->globalJsFiles);
        $frontendAssets['globalCssFiles'] = array_merge($frontendAssets['globalCssFiles'] ?? [], $this->globalCssFiles);

        // merge in page specific assets
        foreach ($this->jsFiles as $page => $files) {
            if (!isset($frontendAssets['jsFiles'][$page])) {
                $frontendAssets['jsFiles'][$page] = [];
            }

            $frontendAssets['jsFiles'][$page] = array_merge($frontendAssets['jsFiles'][$page], $files);
        }

        foreach ($this->cssFiles as $page => $files) {
            if (!isset($frontendAssets['cssFiles'][$page])) {
                $frontendAssets['cssFiles'][$page] = [];
            }

            $frontendAssets['cssFiles'][$page] = array_merge($frontendAssets['cssFiles'][$page], $files);
        }

        $container->set('FrontendAssets', $frontendAssets);
    }

    public function js(string $path, array $pages = []): self {
        if (empty($pages)) {
            $this->globalJsFiles[] = $this->trimPath($path);
        } else {
            foreach ($pages as $page) {
                if (!isset($this->jsFiles[$page])) {
                    $this->jsFiles[$page] = [];
                }

                $this->jsFiles[$page][] = $this->trimPath($path);
            }
        }

        return $this;
    }

    public function css(string $path, array $pages = []): self {
        if (empty($pages)) {
            $this->globalCssFiles[] = $this->trimPath($path);
        } else {
            foreach ($pages as $page) {
                if (!isset($this->cssFiles[$page])) {
                    $this->cssFiles[$page] = [];
                }

                $this->cssFiles[$page][] = $this->trimPath($path);
            }
        }

        return $this;
    }

    private function trimPath(string $path): string {
        return substr($path, strpos($path, '/vendor'));
    }
}