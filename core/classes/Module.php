<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Modules class
 */

abstract class Module {
    
    private static $_modules = array();

    private $_name, 
            $_author,  
            $_version, 
            $_nameless_version,
            $_load_before,
            $_load_after;

    public function __construct($module, $name, $author, $version, $nameless_version, $load_before = array(), $load_after = array()) {
        self::$_modules[] = $module;
        $this->_name = $name;
        $this->_author = $author;
        $this->_version = $version;
        $this->_nameless_version = $nameless_version;

        // All modules should load after core
        if ($name != 'Core') {
            $load_after[] = 'Core';
        }

        $this->_load_before = $load_before;
        $this->_load_after = $load_after;
    }

    /**
     * Set the name of this module.
     * 
     * @param string $name New name.
     */
    protected final function setName($name) {
        $this->_name = $name;
    }

    /**
     * Set version of this module.
     * 
     * @param string $version Version to set.
     */
    protected final function setVersion($version) {
        $this->_version = $version;
    }

    /**
     * Set NamelessMC version of this module.
     * 
     * @param string $nameless_version NamelessMC version to set.
     */
    protected final function setNamelessVersion($nameless_version) {
        $this->_nameless_version = $nameless_version;
    }

    protected final function setAuthor($author) {
        $this->_author = $author;
    }

    abstract function onInstall();
    abstract function onUninstall(); // TODO: Implement
    abstract function onEnable();
    abstract function onDisable();
    abstract function onPageLoad($user, $pages, $cache, $smarty, $navs, $widgets, $template);

    /**
     * Call `onPageLoad()` function for all registered modules.
     * 
     * @param User $user User viewing the page.
     * @param Pages $pages Instance of pages class.
     * @param Cache $cache Instance of cache to pass.
     * @param Smarty $smarty Instance of smarty to pass.
     * @param array $navs Array of loaded navigation menus.
     * @param Widgets $widgets Instance of widget class to pass.
     * @param TemplateBase|null $template Template to pass.
     */
    public static function loadPage($user, $pages, $cache, $smarty, $navs, $widgets, $template = null) {
        foreach (self::$_modules as $module) {
            $module->onPageLoad($user, $pages, $cache, $smarty, $navs, $widgets, $template);
        }
    }

    public static function getModules() {
        return self::$_modules;
    }

    public function getName() {
        return $this->_name;
    }

    public function getAuthor() {
        return $this->_author;
    }

    public function getVersion() {
        return $this->_version;
    }

    public function getNamelessVersion() {
        return $this->_nameless_version;
    }

    public function getLoadBefore() {
        return $this->_load_before;
    }

    public function getLoadAfter() {
        return $this->_load_after;
    }

    private static function findBeforeAfter($modules, $current) {
        $before = array($current);
        $after = array();
        $found = false;

        foreach ($modules as $module) {
            if ($found) {
                $after[] = $module;
            } else if ($module == $current) {
                $found = true;
            } else {
                $before[] = $module;
            }
        }

        return array($before, $after);
    }

    /**
     * Determine loading arrangement of modules.
     * 
     * @return array Array with module order and any failed modules.
     */
    public static function determineModuleOrder() {
        $module_order = array('Core');
        $failed = array();

        foreach (self::$_modules as $module) {
            if ($module->getName() == 'Core') continue;

            for ($n = 0; $n < count($module_order); $n++) {
                $before_after = self::findBeforeAfter($module_order, $module_order[$n]);

                if (!array_diff($module->getLoadAfter(), $before_after[0]) && !array_diff($module->getLoadBefore(), $before_after[1])) {
                    array_splice($module_order, $n + 1, 0, $module->getName());
                    continue 2;
                }
            }

            $failed[] = $module->getName();
        }

        return array('modules' => $module_order, 'failed' => $failed);
    }
}
