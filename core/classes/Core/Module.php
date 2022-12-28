<?php
declare(strict_types=1);

/**
 * Module base class as well as management class.
 *
 * @package NamelessMC\Core
 * @author Samerton
 * @version 2.0.0-pr13
 * @license MIT
 */
abstract class Module {

    /**
     * @var Module[] Array of all modules
     */
    private static iterable $_modules = [];

    private string $_name;
    private string $_author;
    private string $_version;
    private string $_nameless_version;
    private array $_load_before;
    private array $_load_after;

    /**
     * @param Module $module
     * @param string $name
     * @param string $author
     * @param string $version
     * @param string $nameless_version
     * @param array $load_before
     * @param array $load_after
     */
    public function __construct(
        Module $module,
        string $name,
        string $author,
        string $version,
        string $nameless_version,
        array  $load_before = [],
        array  $load_after = []
    ) {
        self::$_modules[] = $module;
        $this->_name = $name;
        $this->_author = $author;
        $this->_version = $version;
        $this->_nameless_version = $nameless_version;

        // All modules should load after core
        if ($name !== 'Core') {
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
     * @param list{Navigation, array, array} $navs Array of loaded navigation menus.
     * @param Widgets $widgets Instance of widget class to pass.
     * @param TemplateBase $template Template to pass.
     */
    public static function loadPage(User $user, Pages $pages, Cache $cache, Smarty $smarty, $navs, Widgets $widgets, TemplateBase $template): void {
        foreach (self::getModules() as $module) {
            $module->onPageLoad($user, $pages, $cache, $smarty, $navs, $widgets, $template);
        }
    }

    /**
     * Calls the `onPageLoad()` function for all registered modules, and assigns success and error messages to the template.
     *
     * @param User $user User viewing the page.
     * @param Pages $pages Instance of pages class.
     * @param Cache $cache Instance of cache to pass.
     * @param Smarty $smarty Instance of smarty to pass.
     * @param list{array, array, array} $navs Array of loaded navigation menus.
     * @param Widgets $widgets Instance of widget class to pass.
     * @param TemplateBase $template Template to pass.
     * @param Language $language Language instance for translating success and error messages.
     * @param string|null $success_message Success message to pass to the template.
     * @param string[]|null $error_messages Array of error messages to pass to the template.
     */
    public static function loadPageWithMessages(User $user, Pages $pages, Cache $cache, Smarty $smarty, $navs, Widgets $widgets, TemplateBase $template, Language $language, ?string $success_message, ?array $error_messages): void {
        foreach (self::getModules() as $module) {
            $module->onPageLoad($user, $pages, $cache, $smarty, $navs, $widgets, $template);
        }

        if (isset($success_message)) {
            $smarty->assign([
                'SUCCESS' => $success_message,
                'SUCCESS_TITLE' => $language->get('general', 'success')
            ]);
        } else if (isset($error_messages) && count($error_messages)) {
            $smarty->assign([
                'ERRORS' => $error_messages,
                'ERRORS_TITLE' => $language->get('general', 'error')
            ]);
        }
    }

    /** @return Module[] */
    public static function getModules(): iterable {
        return self::$_modules;
    }

    /**
     * Handle page loading for this module.
     * Often used to register permissions, sitemaps, widgets, etc.
     *
     * @param User $user User viewing the page.
     * @param Pages $pages Instance of pages class.
     * @param Cache $cache Instance of cache to pass.
     * @param Smarty $smarty Instance of smarty to pass.
     * @param Navigation[] $navs Array of loaded navigation menus.
     * @param Widgets $widgets Instance of widget class to pass.
     * @param TemplateBase|null $template Active template to render.
     */
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
            if ($module->getName() === 'Core') {
                continue;
            }

            foreach ($module_order as $n => $nValue) {
                $before_after = Util::findBeforeAfter($module_order, $nValue);

                if (!array_diff($module->getLoadAfter(), $before_after[0]) && !array_diff($module->getLoadBefore(), $before_after[1])) {
                    array_splice($module_order, $n + 1, 0, $module->getName());
                    continue 2;
                }
            }

            $failed[] = $module->getName();
        }

        return ['modules' => $module_order, 'failed' => $failed];
    }

    /**
     *
     * @return string
     */
    public function getName(): string {
        return $this->_name;
    }

    /**
     * Get the names of modules that this module should load after.
     *
     * @return array Array of module names that this module should load after.
     */
    public function getLoadAfter(): array {
        return $this->_load_after;
    }

    /**
     * Get the names of modules that this module should load before.
     *
     * @return array Array of module names that this module should load before.
     */
    public function getLoadBefore(): array {
        return $this->_load_before;
    }

    /**
     *
     * @return void
     */
    abstract public function onInstall(): void;

    /**
     *
     * @return void
     */
    abstract public function onUninstall(): void;

    /**
     *
     * @return void
     */
    abstract public function onEnable(): void;

    /**
     *
     * @return void
     */
    abstract public function onDisable(): void;

    /**
     * Get debug information to display on the external debug link page.
     *
     * @return array Debug information for this module.
     */
    abstract public function getDebugInfo(): array;

    /**
     * Get this module's author.
     *
     * @return string The author of this module.
     */
    public function getAuthor(): string {
        return $this->_author;
    }

    /**
     * Get this module's version.
     *
     * @return string The version of this module.
     */
    public function getVersion(): string {
        return $this->_version;
    }

    /**
     * Get this module's supported NamelessMC version.
     *
     * @return string The supported NamelessMC version of this module.
     */
    public function getNamelessVersion(): string {
        return $this->_nameless_version;
    }
}
