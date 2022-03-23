<?php

class UserDataProvider {

    /** @var array<string, array<string, Closure>> */
    private static array $providers = [];

    /**
     * @param string $module
     * @param Closure(User, string): mixed $provider
     * @return void
     */
    public static function registerGetter(string $module, Closure $provider): void {
        self::$providers[$module]['getter'] = $provider;
    }

    /**
     * @param string $module
     * @param Closure(User, string, string): void $provider
     * @return void
     */
    public static function registerSetter(string $module, Closure $provider): void {
        self::$providers[$module]['setter'] = $provider;
    }

    public static function get(User $user, string $module, string $variable): ?string {
        if (isset(self::$providers[$module]['getter'])) {
            return self::$providers[$module]['getter']($user, $variable);
        }

        return null;
    }

    public static function set(User $user, string $module, string $variable, $value): void {
        if (isset(self::$providers[$module]['setter'])) {
            self::$providers[$module]['setter']($user, $variable, $value);
        }
    }
}
