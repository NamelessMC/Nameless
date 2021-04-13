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

    protected $_name,
              $_pages,
              $_location,
              $_content,
              $_description,
              $_module,
              $_order,
              $_settings = null;

    public function __construct($pages = array()) {
        $this->_pages = $pages;
    }

    /**
     * Get the name of this widget.
     *
     * @return string Name of widget.
     */
    public function getName() {
        return $this->_name;
    }

    /**
     * Get pages this widget is enabled on.
     *
     * @return array Pages this widget is enabled on.
     */
    public function getPages() {
        return $this->_pages;
    }

    /**
     * Get the location (`left` or `right`) that this widget will be displayed on.
     *
     * @return string Location of widget.
     */
    public function getLocation() {
        return $this->_location;
    }

    /**
     * Render this widget to be displayed on a template page.
     *
     * @return string Content/HTML of this widget.
     */
    public function display() {
        return $this->_content;
    }

    /**
     * Get the description of this widget.
     *
     * @return string Description of widget.
     */
    public function getDescription() {
        return $this->_description;
    }

    /**
     * Get the module of this widget.
     *
     * @return string Name of module.
     */
    public function getModule() {
        return $this->_module;
    }

    /**
     * Get the URL for settings of this widget.
     *
     * @return string Widget settings URL.
     */
    public function getSettings() {
        return $this->_settings;
    }

    /**
     * Get the display order of this widget.
     *
     * @return int Display order of widget.
     */
    public function getOrder() {
        return $this->_order;
    }

    /**
     * Generate this widget's $_content.
     */
    public abstract function initialise();
}
