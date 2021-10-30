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

    private array $_pages;
	private array $_active_page;
    private array $_sm_methods;
    private array $_ajax_requests = [];

    private int $_id = 1;

    /**
     * Defines a page and assigns it to a module.
     *
     * @param string $module Module which the page belongs to.
     * @param string $url URL string.
     * @param string $file Path (from module folder) to page file.
     * @param string $name Name of page.
     * @param bool $widgets Can widgets be used on the page? Default false.
     */
    public function add(string $module, string $url, string $file, string $name = '', bool $widgets = false): void {
        $this->_pages[$url] = [
            'module' => $module,
            'file' => $file,
            'name' => $name,
            'widgets' => $widgets,
            'id' => $this->_id++
        ];
    }

    /**
     * Defines a custom page.
     *
     * @param string $url URL string.
     * @param string $name Name of page.
     * @param bool|null $widgets Can widgets be used on the page? Default false.
     */
    public function addCustom(string $url, string $name, bool $widgets = false) {
        $this->_pages[$url] = [
            'module' => 'Core',
            'file' => 'custom.php',
            'name' => $name,
            'widgets' => $widgets,
            'custom' => true,
            'id' => $this->_id++
        ];
    }

    /**
     * Get array of all pages.
     * 
     * @return array All pages.
     */
    public function returnPages(): array {
        return $this->_pages;
    }

    /**
     * Return pages which allow widgets.
     * 
     * @return array All pages which allow widgets.
     */
    public function returnWidgetPages(): array {
        $ret = [];

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
    public function registerSitemapMethod(string $file, string $method): void {
        if ($file && $method) {
            if (!isset($this->_sm_methods[$file])) {
                $this->_sm_methods[$file] = [];
            }

            $this->_sm_methods[$file] = $method;
        }
    }

    /**
     * Get registered sitemap methods.
     * 
     * @return array Array of sitemap methods.
     */
    public function getSitemapMethods(): array {
        return $this->_sm_methods;
    }

    /**
     * Get page by ID
     *
     * @param int|null $page_id ID of page to find.
     *
     * @return array Page information.
     */
    public function getPageById(int $page_id = null): array {
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
     * @param string|null $url URL of page to find.
     *
     * @return array Page information.
     */
    public function getPageByURL(string $url = null): array {
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
	public function setActivePage(array $page):  void {
		$this->_active_page = $page;
	}

    /**
     * Get the page details the user currently viewing.
     * Not used internally.
     *
     * @return array Details of current page.
     */
	public function getActivePage(): array {
		return $this->_active_page;
	}

    /**
     * Add a script for Javascript to perform a GET request to.
     *
     * @param string|null $script URL of js script to add.
     */
    public function addAjaxScript(string $script = null): void {
        if ($script) {
            $this->_ajax_requests[] = $script;
        }
    }

    /**
     * Get scripts for Javascript to perform a GET request to.
     *
     * @return array All registered ajax script URLs.
     */
    public function getAjaxScripts(): array {
        return $this->_ajax_requests;
    }
}
