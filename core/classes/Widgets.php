<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr5
 *
 *  License: MIT
 *
 *  Widget class
 */
class Widgets {
    private $_widgets = array(),
            $_enabled = array(),
            $_cache,
            $_db,
            $_name;

    public function __construct($cache, $name = 'core'){
        // Assign name to use in cache file
        $this->_name = $name;
        $this->_cache = $cache;
        $this->_cache->setCache($this->_name . '-widgets');

        $this->_db = DB::getInstance();

        $enabled = $this->_cache->retrieve('enabled');
        if(count($enabled))
            $this->_enabled = $enabled;
    }

    /*
     *  Add a widget
     */
    public function add($widget){
        // Add widget to added widget list
        $this->_widgets[$widget->getName()] = $widget;
    }

    /*
     *  Enable a widget
     */
    public function enable($widget){
        // Add widget to enabled widget list
        $this->_enabled[$widget->getName()] = true;
        $this->_cache->setCache($this->_name . '-widgets');
        $this->_cache->store('enabled', $this->_enabled);

        // Update database
        $widget_id = $this->_db->get('widgets', array('name', '=', $widget->getName()));
        if($widget_id->count()){
            $widget_id = $widget_id->first();
            $this->_db->update('widgets', $widget_id->id, array('enabled' => 1));
        }
    }

    /*
     *  Disable a widget
     */
    public function disable($widget){
        unset($this->_enabled[$widget->getName()]);
        $this->_cache->setCache($this->_name . '-widgets');
        $this->_cache->store('enabled', $this->_enabled);

        // Update database
        $widget_id = $this->_db->get('widgets', array('name', '=', $widget->getName()));
        if($widget_id->count()){
            $widget_id = $widget_id->first();
            $this->_db->update('widgets', $widget_id->id, array('enabled' => 0));
        }
    }

    /*
     *  Get a single widget by name
     */
    public function getWidget($name = null){
        if($name)
            if(array_key_exists($name, $this->_widgets))
                return $this->_widgets[$name];

        return null;
    }

    /*
     *  Get code for all enabled widgets on the current page
     */
    public function getWidgets(){
        $ret = array();

        $widgets = $this->getAll();

        foreach($widgets as $item)
            if(array_key_exists($item->getName(), $this->_enabled) && is_array($item->getPages()) && in_array((defined('PAGE') ? PAGE : 'index'), $item->getPages())){
            	$item->initialise();
	            $ret[] = $item->display();
            }

        return $ret;
    }

    /*
     *  List all widgets
     */
    public function getAll(){
        $widgets = $this->_widgets;
        uasort($widgets, function($a, $b){
            return $a->getOrder() - $b->getOrder();
        });
        return $widgets;
    }

    /*
     *  Get all enabled widget names
     */
    public function getAllEnabledNames(){
    	return $this->_enabled;
    }

    /*
     *  Is a widget enabled?
     */
    public function isEnabled($widget){
        return (array_key_exists($widget->getName(), $this->_enabled));
    }

    /*
     *  Get a list of pages a widget is enabled on, by name
     */
    public function getPages($name){
        $pages = $this->_db->get('widgets', array('name', '=', $name));
        if($pages->count()){
            $pages = $pages->first();
            return json_decode($pages->pages, true);
        }
        return array();
    }

    /*
     *  Get the name of this collection of widgets
     */
    public function getName(){
    	return $this->_name;
    }
}