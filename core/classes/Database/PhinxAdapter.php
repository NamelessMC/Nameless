<?php

class PhinxAdapter {

    public static function getConfig(string $value): string {
        require __DIR__ . '/../../../core/config.php';
        return $conf['mysql'][$value];
    }

    public static function migrate(): string {
        return self::handle('getMigrate');
    }

    public static function isMigrated(): bool {
        return !str_contains(self::handle('getStatus'), 'down');
    }

    private static function handle(string $command): string {
        return (new Phinx\Wrapper\TextWrapper(require __DIR__ . '/../../../vendor/robmorgan/phinx/app/phinx.php'))
            ->{$command}('nameless');
    }

}
