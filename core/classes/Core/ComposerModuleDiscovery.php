<?php
class ComposerModuleDiscovery
{
    private static array $_modules = [];

    /**
     * @return ComposerModuleWrapper[]
     */
    public static function discoverModules(): array
    {
        // Avoid registering multiple instances of same modules if this is called twice in a
        // single request; such as on the panel modules page
        if (!empty(self::$_modules)) {
            return self::$_modules;
        }

        $modules = [];
        $packages = json_decode(file_get_contents(ROOT_PATH . '/vendor/composer/installed.json'), true)['packages'];
        foreach ($packages as $package) {
            if ($package['type'] === 'nameless-module') {
                $modules[] = self::fromPackage($package);
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
        $extenders = require_once ROOT_PATH . '/vendor/' . $composerModule->getPackageName() . '/module.php';
        foreach ($extenders as $extender) {
            $extender->setModule($composerModule)->extend($container);
        }
    }

    public static function fromPackage(array $composerPackage): ComposerModuleWrapper
    {
        return new ComposerModuleWrapper(
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
