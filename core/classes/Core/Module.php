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
    
    private static iterable $_modules = [];

    private string $_name;
    private string $_author;
    private string $_version;
    private string $_nameless_version;
    private array $_load_before;
    private array $_load_after;

    public function __construct(Module $module, string $name, string $author, string $version, string $nameless_version, array $load_before = [], array $load_after = []) {
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
    protected final function setName(string $name): void {
        $this->_name = $name;
    }

    /**
     * Set version of this module.
     * 
     * @param string $version Version to set.
     */
    protected final function setVersion(string $version): void {
        $this->_version = $version;
    }

    /**
     * Set NamelessMC version of this module.
     * 
     * @param string $nameless_version NamelessMC version to set.
     */
    protected final function setNamelessVersion(string $nameless_version): void {
        $this->_nameless_version = $nameless_version;
    }

    protected final function setAuthor(string $author): void {
        $this->_author = $author;
    }

    abstract function onInstall();
    abstract function onUninstall(); // TODO: Implement
    abstract function onEnable();
    abstract function onDisable();
    abstract function onPageLoad(User $user, Pages $pages, Cache $cache, Smarty $smarty, iterable $navs, Widgets $widgets, ?TemplateBase $template);
    abstract function getDebugInfo(): array;

    /**
     * Call `onPageLoad()` function for all registered modules.
     * 
     * @param User $user User viewing the page.
     * @param Pages $pages Instance of pages class.
     * @param Cache $cache Instance of cache to pass.
     * @param Smarty $smarty Instance of smarty to pass.
     * @param Navigation[] $navs Array of loaded navigation menus.
     * @param Widgets $widgets Instance of widget class to pass.
     * @param TemplateBase $template Template to pass.
     */
    public static function loadPage(User $user, Pages $pages, Cache $cache, Smarty $smarty, iterable $navs, Widgets $widgets, TemplateBase $template) {
        foreach (self::getModules() as $module) {
            $module->onPageLoad($user, $pages, $cache, $smarty, $navs, $widgets, $template);
        }
    }

    /** @return Module[] */
    public static function getModules(): iterable {
        return self::$_modules;
    }

    public function getName(): string {
        return $this->_name;
    }

    public function getAuthor(): string {
        return $this->_author;
    }

    public function getVersion(): string {
        return $this->_version;
    }

    public function getNamelessVersion(): string {
        return $this->_nameless_version;
    }

    public function getLoadBefore(): array {
        return $this->_load_before;
    }

    public function getLoadAfter(): array {
        return $this->_load_after;
    }

    private static function findBeforeAfter(array $modules, string $current): array {
        $before = [$current];
        $after = [];
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

        return [$before, $after];
    }

    /**
     * Determine loading arrangement of modules.
     * 
     * @return array Array with module order and any failed modules.
     */
    public static function determineModuleOrder(): array {
        $module_order = ['Core'];
        $failed = [];

        foreach (self::getModules() as $module) {
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

        return ['modules' => $module_order, 'failed' => $failed];
    }
}
