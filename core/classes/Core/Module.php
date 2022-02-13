<?php
/**
 * Module base class
 *
 * @package NamelessMC\Core
 * @author Samerton
 * @version 2.0.0-pr13
 * @license MIT
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
    public static function loadPage(User $user, Pages $pages, Cache $cache, Smarty $smarty, iterable $navs, Widgets $widgets, TemplateBase $template): void {
        foreach (self::getModules() as $module) {
            $module->onPageLoad($user, $pages, $cache, $smarty, $navs, $widgets, $template);
        }
    }

    /** @return Module[] */
    public static function getModules(): iterable {
        return self::$_modules;
    }

    abstract public function onPageLoad(User $user, Pages $pages, Cache $cache, Smarty $smarty, iterable $navs, Widgets $widgets, ?TemplateBase $template);

    /**
     * Determine loading arrangement of modules.
     *
     * @return array Array with module order and any failed modules.
     */
    public static function determineModuleOrder(): array {
        $module_order = ['Core'];
        $failed = [];

        foreach (self::getModules() as $module) {
            if ($module->getName() == 'Core') {
                continue;
            }

            foreach ($module_order as $n => $nValue) {
                $before_after = self::findBeforeAfter($module_order, $nValue);

                if (!array_diff($module->getLoadAfter(), $before_after[0]) && !array_diff($module->getLoadBefore(), $before_after[1])) {
                    array_splice($module_order, $n + 1, 0, $module->getName());
                    continue 2;
                }
            }

            $failed[] = $module->getName();
        }

        return ['modules' => $module_order, 'failed' => $failed];
    }

    public function getName(): string {
        return $this->_name;
    }

    /**
     * Set the name of this module.
     *
     * @param string $name New name.
     */
    final protected function setName(string $name): void {
        $this->_name = $name;
    } // TODO: Implement

    private static function findBeforeAfter(array $modules, string $current): array {
        $before = [$current];
        $after = [];
        $found = false;

        foreach ($modules as $module) {
            if ($found) {
                $after[] = $module;
            } else {
                if ($module == $current) {
                    $found = true;
                } else {
                    $before[] = $module;
                }
            }
        }

        return [$before, $after];
    }

    public function getLoadAfter(): array {
        return $this->_load_after;
    }

    public function getLoadBefore(): array {
        return $this->_load_before;
    }

    abstract public function onInstall();

    abstract public function onUninstall();

    abstract public function onEnable();

    abstract public function onDisable();

    abstract public function getDebugInfo(): array;

    public function getAuthor(): string {
        return $this->_author;
    }

    final protected function setAuthor(string $author): void {
        $this->_author = $author;
    }

    public function getVersion(): string {
        return $this->_version;
    }

    /**
     * Set version of this module.
     *
     * @param string $version Version to set.
     */
    final protected function setVersion(string $version): void {
        $this->_version = $version;
    }

    public function getNamelessVersion(): string {
        return $this->_nameless_version;
    }

    /**
     * Set NamelessMC version of this module.
     *
     * @param string $nameless_version NamelessMC version to set.
     */
    final protected function setNamelessVersion(string $nameless_version): void {
        $this->_nameless_version = $nameless_version;
    }
}
