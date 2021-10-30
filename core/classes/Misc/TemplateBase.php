<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Base template class
 */

abstract class TemplateBase {
    
    protected string $_name = '';
    protected string $_version = '';
    protected string $_nameless_version = '';
    protected string $_author = '';
    protected string $_settings = '';
    protected array $_css = [];
    protected array $_js = [];

    public function __construct(string $name, string $version, string $nameless_version, string $author) {
        $this->_name = $name;
        $this->_version = $version;
        $this->_nameless_version = $nameless_version;
        $this->_author = $author;
    }
    
    /**
     * Handle page loading.
     */
    public abstract function onPageLoad();

    /**
     * Add list of CSS files to be loaded on each page load.
     *
     * @param array $files Files to be loaded.
     */
    public function addCSSFiles(array $files): void {
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
    public function addCSSStyle(string $style = null): void {
        if ($style) {
            $this->_css[] = '<style>' . $style . '</style>';
        }
    }

    /**
     * Add list of Javascript files to be loaded on each page load.
     *
     * @param array $files Files to be loaded.
     */
    public function addJSFiles(array $files): void {
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
    public function addJSScript(string $script = null): void {
        if ($script) {
            $this->_js[] = '<script type="text/javascript">' . $script . '</script>';
        }
    }

    /**
     * Get all internal CSS styles.
     *
     * @return array Array of strings of CSS.
     */
    public function getCSS(): array {
        return $this->_css;
    }

    /**
     * Get all internal JS code.
     *
     * @return array Array of strings of JS.
     */
    public function getJS(): array {
        return $this->_js;
    }

    /**
     * Get name of this template.
     *
     * @return string Name of template.
     */
    public function getName(): string {
        return $this->_name;
    }

    /**
     * Get version of this template.
     *
     * @return string Version of template.
     */
    public function getVersion(): string {
        return $this->_version;
    }

    /**
     * Get NamelessMC version of this template.
     *
     * @return string NamelessMC version of template.
     */
    public function getNamelessVersion(): string {
        return $this->_nameless_version;
    }

    /**
     * Get name of author of this template.
     *
     * @return string Author name of template.
     */
    public function getAuthor(): string {
        return $this->_author;
    }

    /**
     * Get settings URL of this template.
     *
     * @return string Settings URL of template.
     */
    public function getSettings(): string {
        return $this->_settings;
    }

    /**
     * Render this template with Smarty engine.
     */
    public function displayTemplate(string $template, Smarty $smarty): void {
        $smarty->assign([
            'TEMPLATE_CSS' => $this->getCSS(),
            'TEMPLATE_JS' => $this->getJS()
        ]);
        $smarty->display($template);
    }

    public function getTemplate(string $template, Smarty $smarty): string {
        $smarty->assign([
            'TEMPLATE_CSS' => $this->getCSS(),
            'TEMPLATE_JS' => $this->getJS()
        ]);

        return $smarty->fetch($template);
    }
}
