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

    /**
     * Defines a page and assigns it to a module.
     *
     * @param string $module Module which the page belongs to.
     * @param string $url URL string.
     * @param string $file Path (from module folder) to page file.
     * @param string|null $name Name of page.
     * @param bool|null $widgets Can widgets be used on the page? Default false.
     */
    public function add($module, $url, $file, $name = '', $widgets = false) {
        $this->_pages[$url] = array(
            'module' => $module,
            'file' => $file,
            'name' => $name,
            'widgets' => $widgets,
            'id' => $this->_id++
        );
    }

    /**
     * Defines a custom page.
     *
     * @param string $url URL string.
     * @param string $name Name of page.
     * @param bool|null $widgets Can widgets be used on the page? Default false.
     */
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

    /**
     * Get array of all pages.
     * 
     * @return array All pages.
     */
    public function returnPages() {
        return $this->_pages;
    }

    /**
     * Return pages which allow widgets.
     * 
     * @return array All pages which allow widgets.
     */
    public function returnWidgetPages() {
        $ret = array();

        foreach ($this->_pages as $page) {
            if (!empty($page['name']) && $page['widgets'] === true) {
                $ret[$page['module']][$page['name']] = true;
            }
        }

        return $ret;
    }

    /**
     * Register a method for sitemap generation.
     */
    public function registerSitemapMethod($file, $method) {
        if ($file && $method) {
            if (!isset($this->_sm_methods[$file])) {
                $this->_sm_methods[$file] = array();
            }

            $this->_sm_methods[$file] = $method;
        }
    }

    /**
     * Get registered sitemap methods.
     * 
     * @return array Array of sitemap methods.
     */
    public function getSitemapMethods() {
        return $this->_sm_methods;
    }

    /**
     * Get page by ID
     *
     * @param int $page_id ID of page to find.
     * @return array Page information.
     */
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

    /**
     * Get page by URL.
     *
     * @param string $url URL of page to find.
     * @return array Page information.
     */
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

    /**
     * Set the page the user currently viewing.
     */
	public function setActivePage($page) {
		$this->_active_page = $page;
	}

    /**
     * Get the page details the user currently viewing.
     * Not used internally.
     *
     * @return array Details of current page.
     */
	public function getActivePage() {
		return $this->_active_page;
	}

    /**
     * Add a script for Javascript to perform a GET request to.
     *
     * @param string $script URL of js script to add.
     */
    public function addAjaxScript($script = null) {
        if ($script) {
            $this->_ajax_requests[] = $script;
        }
    }

    /**
     * Get scripts for Javascript to perform a GET request to.
     *
     * @return array All registered ajax script URLs.
     */
    public function getAjaxScripts() {
        return $this->_ajax_requests;
    }
}
