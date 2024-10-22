<?php
class ComposerModuleDiscovery
{
    private static array $_modules = [];

    /**
     * @return ComposerModuleWrapper[]
     */
    public static function discoverModules(): array
    {
        if (!empty(self::$_modules)) {
            return self::$_modules;
        }

        $modules = [];
        // Check for 1p modules locally
        foreach (scandir(ROOT_PATH . '/modules') as $module) {
            if (!str_starts_with($module, '.') && is_dir(ROOT_PATH . '/modules/' . $module)) {
                if (file_exists(ROOT_PATH . '/modules/' . $module . '/composer.json')) {
                    $package = json_decode(file_get_contents(ROOT_PATH . '/modules/' . $module . '/composer.json'), true);
                    $package['source']['url'] = 'https://github.com/NamelessMC/' . strtolower($module) . '-module';
                    $modules[] = self::fromPackage($package, false);
                }
            }
        }

        // Check for 3p modules installed via composer
        $packages = json_decode(file_get_contents(ROOT_PATH . '/vendor/composer/installed.json'), true)['packages'];
        foreach ($packages as $package) {
            if ($package['type'] === 'nameless-module') {
                $modules[] = self::fromPackage($package, true);
            }
        }

        return self::$_modules = $modules;
    }

    /**
     * @param array $allEnabledModules
     * @param ComposerModuleWrapper[] $composerModules
     */
    public static function bootModules(\DI\Container $container, array $allEnabledModules, array $composerModules): void
    {
        foreach ($composerModules as $composerModule)  {
            if (!in_array($composerModule->getName(), array_column($allEnabledModules, 'name'))) {
                continue;
            }
    
            self::bootModule($container, $composerModule);
        }
    }

    public static function bootModule(\DI\Container $container, ComposerModuleWrapper $composerModule): void
    {
        /** @var NamelessMC\Framework\Extend\BaseExtender[] $extenders */
        $extenders = $composerModule->isInVendor()
            ? require_once ROOT_PATH . '/vendor/' . $composerModule->getPackageName() . '/module.php'
            : require_once ROOT_PATH . '/modules/' . $composerModule->getName() . '/module.php';
        foreach ($extenders as $extender) {
            $extender->setModule($composerModule)->extend($container);
        }
    }

    public static function fromPackage(array $composerPackage, bool $inVendor): ComposerModuleWrapper
    {
        return new ComposerModuleWrapper(
            $inVendor,
            $composerPackage['name'],
            $composerPackage['extra']['nameless_module']['name'],
            $composerPackage['extra']['nameless_module']['display_name'],
            $composerPackage['authors'][0]['name'],
            $composerPackage['authors'][0]['homepage'],
            $composerPackage['extra']['nameless_module']['version'],
            $composerPackage['extra']['nameless_module']['nameless_version'],
            $composerPackage['source']['url'],
        );
    }
}
