<?php

/**
 * NamelessMC logger class.
 *
 * @package NamelessMC\Core
 * @author Samerton
 * @version 2.3.0
 * @license MIT
 */

use Monolog\Handler\FilterHandler;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger as MonologLogger;

class Logger
{
    private string $_name;
    private MonologLogger $_monolog;

    private static ?Logger $_defaultLogger = null;

    public function __construct(string $name) {
        $this->_name = $name;

        $this->_monolog = new MonologLogger($this->_name);

        // All logger instances must log to file
        // It is possible for other modules to register custom handlers, however the file handler will always be present
        $baseDir = implode(DIRECTORY_SEPARATOR, [ROOT_PATH, 'cache', 'logs', $this->_name]);

        // Debug log
        if (defined('DEBUGGING') && DEBUGGING) {
            $debugHandler = new MaxFileSizeLogHandler("$baseDir/debug.log", Level::Debug);
            $debugFilter = new FilterHandler(
                $debugHandler,
                Level::Debug,
                Level::Debug);

            $this->registerLogHandler($debugFilter);
        }

        $infoHandler = new StreamHandler("$baseDir/info.log", Level::Info);
        $infoFilter = new FilterHandler(
            $infoHandler,
            Level::Info,
            Level::Warning);

        $errorHandler = new StreamHandler("$baseDir/error.log", Level::Error);
        $errorFilter = new FilterHandler(
            $errorHandler,
            Level::Error,
            Level::Critical);

        $this->registerLogHandler($infoFilter);
        $this->registerLogHandler($errorFilter);

        // Debug Bar integration
        if (defined('PHPDEBUGBAR')) {
            DebugBarHelper::getInstance()->addMonologCollector($this->_monolog);
        }
    }

    public function registerLogHandler(HandlerInterface $handler): void
    {
        $this->_monolog->pushHandler($handler);
    }

    public function debug(string $message, array $meta = []): void
    {
        $this->_monolog->debug($message, $meta);
    }

    public function info(string $message, array $meta = []): void
    {
        $this->_monolog->info($message, $meta);
    }

    public function notice(string $message, array $meta = []): void
    {
        $this->_monolog->notice($message, $meta);
    }

    public function warning(string $message, array $meta = []): void
    {
        $this->_monolog->warning($message, $meta);
    }

    public function error(string $message, array $meta = []): void
    {
        $this->_monolog->error($message, $meta);
    }

    public function critical(string $message, array $meta = []): void
    {
        $this->_monolog->critical($message, $meta);
    }

    public static function getDefaultLogger(): ?Logger
    {
        return self::$_defaultLogger;
    }

    public static function setDefaultLogger(Logger $logger): void
    {
        if (isset(self::$_defaultLogger)) {
            throw new Exception('Cannot change the default logger once it is set');
        }

        self::$_defaultLogger = $logger;
    }
}
