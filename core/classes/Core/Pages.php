<?php
/**
 * Contains data about all the registered pages in the application.
 *
 * @package NamelessMC\Core
 * @author Samerton
 * @version 2.0.0-pr8
 * @license MIT
 */
class Pages {

    /**
     * @var array Array of all the registered pages.
     */
    private array $_pages;

    /**
     * @var array Array of data about the active page.
     */
    private array $_active_page = [];

    /**
     * @var array<callable> Array of sitemap files and methods.
     */
    private array $_sm_methods = [];

    /**
     * @var array Array of URLs to call with ajax.
     */
    private array $_ajax_requests = [];

    /**
     * @var int ID of last created page.
     */
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
     * @param bool $widgets Can widgets be used on the page? Default false.
     */
    public function addCustom(string $url, string $name, bool $widgets = false): void {
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
     * @see \SitemapPHP\Sitemap
     *
     * @param callable $method Array callable of the sitemap class and method to execute.
     */
    public function registerSitemapMethod(callable $method): void {
        $this->_sm_methods[] = $method;
    }

    /**
     * Get registered sitemap methods.
     *
     * @return array<callable> Array of sitemap methods.
     */
    public function getSitemapMethods(): array {
        return $this->_sm_methods;
    }

    /**
     * Get page by ID
     *
     * @param int $page_id ID of page to find.
     * @return array Page information.
     */
    public function getPageById(int $page_id): ?array {
        foreach ($this->_pages as $key => $page) {
            if ($page['id'] == $page_id) {
                $page['key'] = $key;
                return $page;
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
    public function getPageByURL(string $url): ?array {
        foreach ($this->_pages as $key => $page) {
            if ($key == $url) {
                $page['key'] = $key;
                return $page;
            }
        }

        return null;
    }

    /**
     * Get the page details the user currently viewing.
     *
     * @return array Details of current page.
     */
    public function getActivePage(): array {
        return $this->_active_page;
    }

    /**
     * Set the page the user currently viewing.
     */
    public function setActivePage(array $page): void {
        $this->_active_page = $page;
    }

    /**
     * Add a script for Javascript to perform a GET request to.
     *
     * @param string $script URL of js script to add.
     */
    public function addAjaxScript(string $script): void {
        $this->_ajax_requests[] = $script;
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
