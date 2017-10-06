<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr2
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
              $_module;

    public function __construct($pages = array()){
        $this->_pages = $pages;
    }

    public function getName(){
        return $this->_name;
    }

    public function getPages(){
        return $this->_pages;
    }

    public function getLocation(){
        return $this->_location;
    }

    public function display(){
        return $this->_content;
    }

    public function getDescription(){
        return $this->_description;
    }

    public function getModule(){
        return $this->_module;
    }
}