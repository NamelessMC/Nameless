<?php

/**
 * Template asset management class.
 *
 * @package NamelessMC\Templates
 * @see TemplateBase
 * @author Aberdeener
 * @version 2.0.0-pr13
 * @license MIT
 */
class AssetResolver extends AssetTree {

    /**
     * @var array<array> Array of assets currently resolved.
     */
    private array $_assets = [];

    /**
     * Add assets to be put in the response HTML.
     *
     * @param string|array<string> $assets The asset(s) to resolve and add. Must be a constant from the `AssetTree` class.
     */
    public function include($assets): void {
        if (!is_array($assets)) {
            $assets = [$assets];
        }

        foreach ($assets as $asset) {
            if ($this->validateAsset($asset)) {
                $this->_assets[$asset] = parent::ASSET_TREE[$asset];
            }
        }
    }

    /**
     * Resolve all the assets as an array of CSS file and JS files to add to
     * the template as `link` or `script` elements respectively.
     *
     * @return array<array<string>> The resolved assets ready to be added to the template.
     */
    public function compile(): array {
        $css = [];
        $js = [];

        $assets = Util::determineOrder(array_map(static function(string $key, array $value) {
            return [
                'name' => $key,
                'after' => $value['depends'] ?? $value['after'] ?? [],
                'before' => $value['before'] ?? [],
            ];
        }, array_keys($this->_assets), array_values($this->_assets)));

        foreach ($assets as $name) {
            $this->gatherAsset($name, $this->_assets[$name], $css, $js);
        }

        return [$css, $js];
    }

    /**
     * Generate URLs for the given asset, and add them to the CSS and JS URL arrays as needed.
     * This will also resolve and gather dependencies for the asset if applicable.
     *
     * @param string $name The name of the asset.
     * @param array $asset The asset to gather.
     * @param array $css Array of CSS assets already resolved to add to.
     * @param array $js Array of JS assets already resolved to add to.
     */
    private function gatherAsset(string $name, array $asset, array &$css, array &$js): void {
        // Load the dependencies first so that they're the first to be added to the HTML
        if (isset($asset['depends'])) {
            foreach ($asset['depends'] as $dependency) {
                // Don't throw an exception if the dependency has already been resolved
                // since it is a dependency, there is a high chance it has already been resolved anyway
                if ($this->validateAsset($dependency, false)) {
                    $this->gatherAsset($dependency, parent::ASSET_TREE[$dependency], $css, $js);
                }
            }
        }

        // Unideal solution to get the correct light/dark Prism dependency
        if ($name === parent::TINYMCE) {
            $prism = defined('DARK_MODE') && DARK_MODE
                ? parent::PRISM_DARK
                : parent::PRISM_LIGHT;

            if ($this->validateAsset($prism, false)) {
                $this->gatherAsset($prism, parent::ASSET_TREE[$prism], $css, $js);
            }
        }

        if (isset($asset['css'])) {
            foreach ($asset['css'] as $cssFile) {
                $css[] = $this->buildPath($cssFile, 'css');
            }
        }

        if (isset($asset['js'])) {
            foreach ($asset['js'] as $jsFile) {
                $js[] = $this->buildPath($jsFile, 'js');
            }
        }

        if (!in_array($name, $this->_assets)) {
            $this->_assets[$name] = $asset;
        }
    }

    /**
     * Validate that an asset name is valid.
     * This checks that it exists in the asset tree, and that it has not already been resolved.
     *
     * @param string $assetName The asset name to validate.
     * @return bool False if the asset name is invalid, true otherwise.
     * @throws InvalidArgumentException If the asset name is invalid or if it has already been resolved.
     */
    private function validateAsset(string $assetName, bool $throw = true): bool {
        if (!array_key_exists($assetName, parent::ASSET_TREE)) {
            if ($throw) {
                throw new InvalidArgumentException('Asset "' . $assetName . '" is not defined');
            }

            return false;
        }

        if (array_key_exists($assetName, $this->_assets)) {
            if ($throw) {
                throw new InvalidArgumentException('Asset "' . $assetName . '" has already been resolved');
            }

            return false;
        }

        return true;
    }

    /**
     * Build an HTML tag for the given asset.
     *
     * @param string $file The file to build the path for.
     * @param string $type The type of the file, either 'css' or 'js'
     * @return string Script or Link HTML tag with a URL to the asset.
     */
    private function buildPath(string $file, string $type): string {
        $href = (defined('CONFIG_PATH')
                ? CONFIG_PATH
                : '')
            . '/core/assets/' . $file;

        if (!file_exists(ROOT_PATH . '/core/assets/' . $file)) {
            throw new InvalidArgumentException('Asset file "' . $href . '" not found');
        }

        if ($type === 'css') {
            return '<link rel="stylesheet" href="' . $href . '">';
        }

        if ($type === 'js') {
            return '<script type="text/javascript" src="' . $href . '"></script>';
        }

        throw new RuntimeException('Unknown asset type: ' . $type);
    }
}
