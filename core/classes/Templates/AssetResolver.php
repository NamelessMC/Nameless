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
     * @var array<string> Array of assets currently resolved.
     */
    private array $_assets = [];

    /**
     * Add assets to be put in the response HTML.
     *
     * @param string|array<string> $assets The asset(s) to resolve and add. Must be a constant from the `AssetTree` class.
     */
    public function resolve($assets): void {
        if (!is_array($assets)) {
            $assets = [$assets];
        }

        foreach ($assets as $asset) {
            $this->validateAsset($asset);

            $this->_assets[$asset] = parent::ASSET_TREE[$asset];
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

        foreach ($this->_assets as $asset) {
            $this->gatherAsset($asset, $css, $js);
        }

        return [$css, $js];
    }

    /**
     * Validate that an asset name is valid.
     * This checks that it exists in the asset tree, and that it has not already been resolved.
     *
     * @param string $assetName The asset name to validate.
     * @throws InvalidArgumentException If the asset name is invalid or if it has already been resolved.
     */
    private function validateAsset(string $assetName): void {
        if (!array_key_exists($assetName, parent::ASSET_TREE)) {
            throw new InvalidArgumentException('Asset "' . $assetName . '" is not defined');
        }

        if (array_key_exists($assetName, $this->_assets)) {
            throw new InvalidArgumentException('Asset "' . $assetName . '" has already been resolved');
        }
    }

    /**
     * Generate URLs for the given asset, and add them to the CSS and JS URL arrays as needed.
     * This will also resolve and gather dependencies for the asset if applicable.
     *
     * @param array $asset The asset to gather.
     * @param array $css Array of CSS assets already resolved to add to.
     * @param array $js Array of JS assets already resolved to add to.
     */
    private function gatherAsset(array $asset, array &$css, array &$js): void {
        foreach ($asset['css'] as $cssFile) {
            $css[] = $this->buildPath($cssFile, 'css');
        }

        foreach ($asset['js'] as $jsFile) {
            $js[] = $this->buildPath($jsFile, 'js');
        }

        foreach ($asset['depends'] as $dependency) {
            $this->validateAsset($dependency);
            $this->gatherAsset(parent::ASSET_TREE[$dependency], $css, $js);
        }
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

        if (!file_exists(ROOT_PATH . $href)) {
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
