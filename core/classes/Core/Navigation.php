<?php
/**
 * Repesents a single navigation menu.
 *
 * @package NamelessMC\Core
 * @author Samerton
 * @version 2.0.0
 * @license MIT
 */
class Navigation {

    /**
     * @var array Top navigation items.
     */
    private array $_topNavbar = [];

    /**
     * @var array Footer navigation items.
     */
    private array $_footerNav = [];

    /**
     * @var bool Whether this nav bar is for StaffCP.
     */
    private bool $_panel;

    public function __construct(bool $panel = false) {
        $this->_panel = $panel;
    }

    /**
     * Add a simple item to this navigation instance.
     *
     * @param string $name Unique name for the navbar item, if the page name equals this the item will display as active.
     * @param string $title Item title.
     * @param string $link HTML href attribute, can be link built with URL class or hyperlink.
     * @param string $location Location to add item to, either 'top' or 'footer' (defaults to 'top').
     * @param string|null $target HTML target attribute (eg '_blank').
     * @param float $order Nav item order (default 10).
     * @param string|null $icon Icon to prepend to nav item.
     */
    public function add(
        string $name,
        string $title,
        string $link,
        string $location = 'top',
        string $target = null,
        float $order = 10,
        ?string $icon = ''
    ): void {
        if ($this->_panel && $location == 'top') {
            // Discard order
            // TODO: only a temporary solution to the link conflict issue in the StaffCP
            if (count($this->_topNavbar)) {
                $key = array_keys($this->_topNavbar)[count($this->_topNavbar) - 1];
                $previous_order = $this->_topNavbar[$key]['order'];
            } else {
                $previous_order = 0;
            }

            $order = $previous_order + 1;
        }

        // Add the link to the navigation
        if ($location === 'top') {
            // Add to top navbar
            $this->_topNavbar[$name] = [
                'title' => $title,
                'link' => $link,
                'target' => $target,
                'order' => $order,
                'icon' => $icon
            ];
        } else {
            // Add to footer navigation
            $this->_footerNav[$name] = [
                'title' => $title,
                'link' => $link,
                'target' => $target,
                'order' => $order,
                'icon' => $icon
            ];
        }
    }

    /**
     * Add a dropdown menu to the navigation.
     *
     * @param string $name Unique name for the dropdown
     * @param string $title Dropdown title
     * @param string $location Location to add item to, either 'top' or 'footer' (defaults to 'top').
     * @param int $order Nav item order (default 10).
     * @param string $icon Icon to prepend to nav item.
     */
    public function addDropdown(string $name, string $title, string $location = 'top', int $order = 10, string $icon = ''): void {
        if ($this->_panel && $location == 'top') {
            // Discard order
            // TODO: only a temporary solution to the link conflict issue in the StaffCP
            if (count($this->_topNavbar)) {
                $key = array_keys($this->_topNavbar)[count($this->_topNavbar) - 1];
                $previous_order = $this->_topNavbar[$key]['order'];
            } else {
                $previous_order = 0;
            }

            $order = $previous_order + 1;
        }

        // Add the dropdown
        if ($location == 'top') {
            // Navbar
            $this->_topNavbar[$name] = [
                'title' => $title,
                'items' => [],
                'order' => $order,
                'icon' => $icon
            ];
        } else {
            // Footer
            $this->_footerNav[$name] = [
                'title' => $title,
                'items' => [],
                'order' => $order,
                'icon' => $icon
            ];
        }
    }

    /**
     * Add an item to a menu dropdown.
     *
     * @param string $dropdown Name of dropdown to add item to.
     * @param string $name Unique name for the item, if the page name equals this the item will display as active.
     * @param string $title Item title.
     * @param string $link HTML href attribute, can be link built with URL class or hyperlink.
     * @param string $location Location to add item to, either 'top' or 'footer' (defaults to 'top').
     * @param string|null $target HTML target attribute (eg '_blank')
     * @param string $icon Icon to prepend to nav item
     * @param int $order Nav item order
     */
    public function addItemToDropdown(string $dropdown, string $name, string $title, string $link, string $location = 'top', string $target = null, string $icon = '', int $order = 10): void {
        // Add the item
        if ($location == 'top' && isset($this->_topNavbar[$dropdown])) {
            // Navbar
            $this->_topNavbar[$dropdown]['items'][$name] = [
                'title' => $title,
                'link' => $link,
                'target' => $target,
                'icon' => $icon,
                'order' => $order,
            ];
        } else if (isset($this->_footerNav[$dropdown])) {
            // Footer
            $this->_footerNav[$dropdown]['items'][$name] = [
                'title' => $title,
                'link' => $link,
                'target' => $target,
                'icon' => $icon,
                'order' => $order,
            ];
        }
    }

    /**
     * Return top navigation.
     *
     * @param string $location Either 'top' or 'footer' (defaults to 'top').
     * @return array Array to pass to template
     */
    public function returnNav(string $location = 'top'): array {
        $return = []; // String to return
        if ($location == 'top') {
            if (count($this->_topNavbar)) {
                foreach ($this->_topNavbar as $key => $item) {
                    $return[$key] = $item;
                    if (defined('PAGE') && PAGE == $key) {
                        $return[$key]['active'] = true;
                    }

                    // Sort dropdown
                    if (isset($return[$key]['items'])) {
                        if (count($return[$key]['items'])) {
                            uasort(
                                $return[$key]['items'],
                                static function ($a, $b) {
                                    if ($a['order'] > $b['order']) {
                                        return 1;
                                    }

                                    if ($a['order'] < $b['order']) {
                                        return -1;
                                    }
                                    return 0;
                                }
                            );
                        } else {
                            unset($return[$key]);
                        }
                    }
                }
            }
        } else if (count($this->_footerNav)) {
            foreach ($this->_footerNav as $key => $item) {
                $return[$key] = $item;
                if (defined('PAGE') && PAGE == $key) {
                    $return[$key]['active'] = true;
                }

                // Sort dropdown
                if (isset($return[$key]['items']) && count($return[$key]['items'])) {
                    uasort(
                        $return[$key]['items'],
                        static function ($a, $b) {
                            if ($a['order'] > $b['order']) {
                                return 1;
                            }

                            if ($a['order'] < $b['order']) {
                                return -1;
                            }
                            return 0;
                        }
                    );
                }
            }
        }

        uasort($return, static function ($a, $b) {
            $result = 0;
            if ($a['order'] > $b['order']) {
                $result = 1;
            } else if ($a['order'] < $b['order']) {
                $result = -1;
            }
            return $result;
        });

        return $return;
    }
}
