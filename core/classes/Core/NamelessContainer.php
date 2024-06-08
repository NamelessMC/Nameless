<?php
use DI\Container;

class NamelessContainer extends Container
{
    private static NamelessContainer $_instance;

    public static function getInstance(): NamelessContainer
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new self();
        }

        self::configure();

        return self::$_instance;
    }

    private static function configure(): void
    {
        self::$_instance->set(Cache::class, function () {
            return new Cache([
                'name' => 'nameless',
                'extension' => '.cache',
                'path' => ROOT_PATH . '/cache/'
            ]);
        });

        self::$_instance->set(DB::class, DB::getInstance());
    }
}