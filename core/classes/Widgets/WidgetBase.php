<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Widget Base class
 */

abstract class WidgetBase {

    protected string $_name;
    protected array $_pages;
    protected ?string $_location;
    protected string $_content;
    protected string $_description;
    protected string $_module;
    protected ?int $_order;
    protected ?string $_settings = null;
    protected ?bool $_requires_cookies = false;
    protected ?Smarty $_smarty = null;

    public function __construct(array $pages = [], ?bool $requires_cookies = false) {
        $this->_pages = $pages;
        $this->_requires_cookies = $requires_cookies;
    }

    /**
     * Get the name of this widget.
     *
     * @return string Name of widget.
     */
    public function getName(): string {
        return $this->_name;
    }

    /**
     * Get pages this widget is enabled on.
     *
     * @return array Pages this widget is enabled on.
     */
    public function getPages(): array {
        return $this->_pages;
    }

    /**
     * Get the location (`left` or `right`) that this widget will be displayed on.
     *
     * @return string Location of widget.
     */
    public function getLocation(): string {
        return $this->_location;
    }

    /**
     * Render this widget to be displayed on a template page.
     *
     * @return string Content/HTML of this widget.
     */
    public function display(): string {
        if (defined('COOKIE_CHECK') && $this->_requires_cookies && !COOKIES_ALLOWED) {
            if ($this->_smarty) {
                return $this->_smarty->fetch('widgets/cookie_notice.tpl');
            }

            return 'This widget requires cookies';
        }
        return $this->_content;
    }

    /**
     * Get the description of this widget.
     *
     * @return string Description of widget.
     */
    public function getDescription(): string {
        return $this->_description;
    }

    /**
     * Get the module of this widget.
     *
     * @return string Name of module.
     */
    public function getModule(): string {
        return $this->_module;
    }

    /**
     * Get the URL for settings of this widget.
     *
     * @return string Widget settings URL.
     */
    public function getSettings(): ?string {
        return $this->_settings;
    }

    /**
     * Get the display order of this widget.
     *
     * @return int Display order of widget.
     */
    public function getOrder(): ?int {
        return $this->_order;
    }

    /**
     * Generate this widget's $_content.
     */
    public abstract function initialise(): void;
}
