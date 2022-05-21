<?php

class PhinxAdapter {

    public static function migrate(): string {
        return (new Phinx\Wrapper\TextWrapper(
            new Phinx\Console\PhinxApplication(),
            [
                'configuration' => __DIR__ . '/../../../core/migrations/phinx.php',
            ]
        ))->getMigrate();
    }

}
