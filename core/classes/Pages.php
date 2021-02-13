<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Pages class
 */

class Pages {

    private $_pages,
			$_active_page,
            $_sm_methods,
            $_ajax_requests = array();

    private $_id = 1;

    // Defines a page and assigns it to a module
    // Params:  $module (string)	- module which the page belongs to
    // 			$url (string) 		- contains URL string
    //			$file (string)		- contains path (from module folder) to page file
    //          $name (string)      - contains name of page (optional)
    //          $widgets (boolean)  - can widgets be used on the page? Default false
    public function add($module, $url, $file, $name = '', $widgets = false) {
        $this->_pages[$url] = array(
            'module' => $module,
            'file' => $file,
            'name' => $name,
            'widgets' => $widgets,
            'id' => $this->_id++
        );
    }

    // Defines a custom page
    // Params:  $url (string)       - contains URL string
    //          $name (string)      - contains name of page
    //          $widgets (boolean)  - can widgets be used on the page? Default false
    public function addCustom($url, $name, $widgets = false) {
        $this->_pages[$url] = array(
            'module' => 'Core',
            'file' => 'custom.php',
            'name' => $name,
            'widgets' => $widgets,
            'custom' => true,
            'id' => $this->_id++
        );
    }

    // Returns the array of all pages
    // No params
    public function returnPages() {
        return $this->_pages;
    }

    // Return pages which allow widgets
    // No params
    public function returnWidgetPages() {
        $ret = array();
        foreach ($this->_pages as $page)
            if (!empty($page['name']) && $page['widgets'] === true)
                $ret[$page['module']][$page['name']] = true;

        return $ret;
    }

    // Register a method for sitemap generation
    public function registerSitemapMethod($file, $method) {
        if ($file && $method) {
            if (!isset($this->_sm_methods[$file]))
                $this->_sm_methods[$file] = array();

            $this->_sm_methods[$file] = $method;
        }
    }

    // Get sitemap methods
    public function getSitemapMethods() {
        return $this->_sm_methods;
    }

    // Get page by ID
    public function getPageById($page_id = null) {
        if ($page_id) {
            foreach ($this->_pages as $key => $page) {
                if ($page['id'] == $page_id) {
                    $page['key'] = $key;
                    return $page;
                }
            }
        }
        return null;
    }
	
    // Get page by URL
    public function getPageByURL($url = null) {
        if ($url) {
            foreach ($this->_pages as $key => $page) {
                if ($key == $url) {
					$page['key'] = $key;
                    return $page;
                }
            }
        }
        return null;
    }
	
	// Set the page the user currently viewing
	public function setActivePage($page) {
		$this->_active_page = $page;
	}
	
	// Get the page details the user currently viewing
	public function getActivePage() {
		return $this->_active_page;
	}

    // Add a script for Javascript to perform a GET request to
    public function addAjaxScript($script = null) {
        if ($script) {
            $this->_ajax_requests[] = $script;
        }
        return false;
    }

    // Get scripts for Javascript to perform a GET request to
    public function getAjaxScripts() {
        return $this->_ajax_requests;
    }
}
