<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Widget class
 */

class Widgets {

    /** @var DB */
    private $_db;

    /** @var Cache */
    private $_cache;
    
    private $_widgets = array(),
            $_enabled = array(),
            $_name;

    public function __construct($cache, $name = 'core') {
        // Assign name to use in cache file
        $this->_name = $name;
        $this->_cache = $cache;
        $this->_cache->setCache($this->_name . '-widgets');

        $this->_db = DB::getInstance();

        $enabled = $this->_cache->retrieve('enabled');
        if ($enabled != null && count($enabled)) {
            $this->_enabled = $enabled;
        }
    }
    
    /**
     * Register a widget to the widget list.
     *
     * @param WidgetBase $widget Instance of widget to register.
     */
    public function add($widget) {
        $this->_widgets[$widget->getName()] = $widget;
    }

    /**
     * Enable a widget.
     *
     * @param WidgetBase $widget Instance of widget to enable.
     */
    public function enable($widget) {
        // Add widget to enabled widget list
        $this->_enabled[$widget->getName()] = true;
        $this->_cache->setCache($this->_name . '-widgets');
        $this->_cache->store('enabled', $this->_enabled);

        // Update database
        $widget_id = $this->_db->get('widgets', array('name', '=', $widget->getName()));
        if ($widget_id->count()) {
            $widget_id = $widget_id->first();
            $this->_db->update('widgets', $widget_id->id, array('enabled' => 1));
        }
    }

    /**
     * Disable a widget.
     *
     * @param WidgetBase $widget Instance of widget to disable.
     */
    public function disable($widget) {
        unset($this->_enabled[$widget->getName()]);
        $this->_cache->setCache($this->_name . '-widgets');
        $this->_cache->store('enabled', $this->_enabled);

        // Update database
        $widget_id = $this->_db->get('widgets', array('name', '=', $widget->getName()));
        if ($widget_id->count()) {
            $widget_id = $widget_id->first();
            $this->_db->update('widgets', $widget_id->id, array('enabled' => 0));
        }
    }

    /**
     * Get a widget by name.
     *
     * @param string $name Name of widget to get.
     * @return WidgetBase|null Instance of widget with same name, null if it doesnt exist.
     */
    public function getWidget($name = null) {
        if ($name) {
            if (array_key_exists($name, $this->_widgets)) {
                return $this->_widgets[$name];
            }
        }

        return null;
    }

    /**
     * Get code for all enabled widgets on the current page.
     *
     * @param string $location Either `left` or `right`.
     * @return array List of HTML to be displayed.
     */
    public function getWidgets($location = 'right') {
        $ret = array();

        $widgets = $this->getAll();

        foreach($widgets as $item) {
            if (array_key_exists($item->getName(), $this->_enabled)
                && $item->getLocation() == $location
                && is_array($item->getPages())
                && ((defined('CUSTOM_PAGE') && in_array(CUSTOM_PAGE, $item->getPages()))
                    || in_array((defined('PAGE') ? PAGE : 'index'), $item->getPages()))
            ) {
                $item->initialise();
                $ret[] = $item->display();
            }
        }

        return $ret;
    }

    /**
     * List all widgets, sorted by their order.
     *
     * @return WidgetBase[] List of widgets.
     */
    public function getAll() {
        $widgets = $this->_widgets;
        
        uasort($widgets, function($a, $b) {
            return $a->getOrder() - $b->getOrder();
        });

        return $widgets;
    }
    
    /**
     * Get all enabled widget names.
     * Not used internally.
     *
     * @return array List of enabled widget names.
     */
    public function getAllEnabledNames() {
        return array_keys($this->_enabled);
    }
 
    /**
     * Check if widget is enabled or not.
     *
     * @param WidgetBase $widget Instance of widget to check.
     * @return bool Whether this widget is enabled or not.
     */
    public function isEnabled($widget) {
        return array_key_exists($widget->getName(), $this->_enabled);
    }

    /**
     * Get a list of pages a widget is enabled on.
     *
     * @param string $name Name of widget to get pages for.
     * @return array List of page names.
     */
    public function getPages($name) {
        $pages = $this->_db->get('widgets', array('name', '=', $name));

        if ($pages->count()) {
            $pages = $pages->first();
            return json_decode($pages->pages, true);
        }

        return array();
    }

    /**
     * Get the name of this collection of widgets.
     * Not used internally.
     *
     * @return string Name of this instance.
     */
    public function getName() {
        return $this->_name;
    }
}
