<?php
/**
 * Base class templates should extend to add functionality.
 *
 * @package NamelessMC\Templates
 * @author Samerton
 * @version 2.2.0
 * @license MIT
 */
abstract class TemplateBase
{
    /**
     * @var string The template name
     */
    protected string $_name = '';

    /**
     * @var string The template version
     */
    protected string $_version = '';

    /**
     * @var string The NamelessMC version this template supports.
     */
    protected string $_nameless_version = '';

    /**
     * @var string The template author name (supports HTML)
     */
    protected string $_author = '';

    /**
     * @var string The template settings URL.
     */
    protected string $_settings = '';

    protected AssetResolver $_assets_resolver;

    /**
     * @var array Array of CSS scripts to add to the template.
     */
    protected array $_css = [];

    /**
     * @var array Array of JS scripts to add to the template.
     */
    protected array $_js = [];

    /** @var TemplateEngine Template engine instance */
    protected TemplateEngine $_engine;

    public function __construct(string $name, string $version, string $nameless_version, string $author)
    {
        $this->_name = $name;
        $this->_version = $version;
        $this->_nameless_version = $nameless_version;
        $this->_author = $author;

        /*
         * Temporary assignment to Smarty template engine for backwards compatibility for templates which extend TemplateBase
         * This will be removed in 2.3.0 - breaking change!
         */
        if (!isset($this->_engine)) {
            $this->_engine = new SmartyTemplateEngine(ROOT_PATH . '/custom/templates/' . $name);
        }
    }

    /**
     * Handle page loading.
     */
    abstract public function onPageLoad();

    public function assets(): AssetResolver
    {
        return $this->_assets_resolver ??= new AssetResolver();
    }

    /**
     * Add list of CSS files to be loaded on each page load.
     *
     * @param array $files Files to be loaded.
     */
    public function addCSSFiles(array $files): void
    {
        if (count($files)) {
            foreach ($files as $href => $file) {
                $this->_css[] = '
                <link' . (isset($file['rel']) ? ' rel="' . $file['rel'] . '"' : ' rel="stylesheet"') . ' 
                href="' . $href . '"' .
                    (isset($file['integrity']) ? ' integrity="' . $file['integrity'] . '"' : '') .
                    (isset($file['crossorigin']) ? ' crossorigin="' . $file['crossorigin'] . '"' : '') .
                    (isset($file['as']) ? ' as="' . $file['as'] . '"' : '') .
                    (isset($file['onload']) ? ' onload="' . $file['onload'] . '"' : '') .
                    '>';
            }
        }
    }

    /**
     * Add internal CSS styling to this page load.
     *
     * @param string|null $style Styling to add.
     */
    public function addCSSStyle(string $style = null): void
    {
        if ($style) {
            $this->_css[] = '<style>' . $style . '</style>';
        }
    }

    /**
     * Add list of Javascript files to be loaded on each page load.
     *
     * @param array $files Files to be loaded.
     */
    public function addJSFiles(array $files): void
    {
        if (count($files)) {
            foreach ($files as $href => $file) {
                $this->_js[] = '
                <script type="text/javascript" 
                    src="' . $href . '"' .
                    (isset($file['integrity']) ? ' integrity="' . $file['integrity'] . '"' : '') .
                    (isset($file['crossorigin']) ? 'crossorigin="' . $file['crossorigin'] . '"' : '') .
                    ((isset($file['defer']) && $file['defer']) ? ' defer' : '') .
                    ((isset($file['async']) && $file['async']) ? ' async' : '') .
                    '></script>';
            }
        }
    }

    /**
     * Add internal JS code to this page load.
     *
     * @param string|null $script
     */
    public function addJSScript(string $script = null): void
    {
        if ($script) {
            $this->_js[] = '<script type="text/javascript">' . $script . '</script>';
        }
    }

    /**
     * Get name of this template.
     *
     * @return string Name of template.
     */
    public function getName(): string
    {
        return $this->_name;
    }

    /**
     * Get version of this template.
     *
     * @return string Version of template.
     */
    public function getVersion(): string
    {
        return $this->_version;
    }

    /**
     * Get NamelessMC version of this template.
     *
     * @return string NamelessMC version of template.
     */
    public function getNamelessVersion(): string
    {
        return $this->_nameless_version;
    }

    /**
     * Get name of author of this template.
     *
     * @return string Author name of template.
     */
    public function getAuthor(): string
    {
        return $this->_author;
    }

    /**
     * Get settings URL of this template.
     *
     * @return string Settings URL of template.
     */
    public function getSettings(): string
    {
        return $this->_settings;
    }

    /**
     * Render this template.
     *
     * @param string $template Template file to render, relative to template base directory
     */
    public function displayTemplate(string $template): void
    {
        [$css, $js] = $this->assets()->compile();

        // Put the assets at the start of the arrays, so they load first (SBAdmin requires JQuery first, etc.)
        array_unshift($this->_css, ...$css);
        array_unshift($this->_js, ...$js);

        $this->_engine->addVariables([
            'TEMPLATE_CSS' => $this->getCSS(),
            'TEMPLATE_JS' => $this->getJS(),
        ]);

        if (defined('PHPDEBUGBAR') && PHPDEBUGBAR) {
            $debugBar = DebugBarHelper::getInstance()->getDebugBar()->getJavascriptRenderer();
            $this->_engine->addVariables([
                'DEBUGBAR_JS' => $debugBar->renderHead(),
                'DEBUGBAR_HTML' => $debugBar->render(),
            ]);
        }

        $this->_engine->render($template);
    }

    /**
     * Get all internal CSS styles.
     *
     * @return array Array of strings of CSS.
     */
    public function getCSS(): array
    {
        return $this->_css;
    }

    /**
     * Get all internal JS code.
     *
     * @return array Array of strings of JS.
     */
    public function getJS(): array
    {
        return $this->_js;
    }

    /**
     * Fetches template HTML instead of rendering it.
     *
     * @param  string $template
     * @return string Generated HTML
     */
    public function getTemplate(string $template): string
    {
        $this->_engine->addVariables([
            'TEMPLATE_CSS' => $this->getCSS(),
            'TEMPLATE_JS' => $this->getJS(),
        ]);

        return $this->_engine->fetch($template);
    }

    /**
     * Get template engine.
     *
     * @return TemplateEngine
     */
    public function getEngine(): TemplateEngine
    {
        return $this->_engine;
    }
}
