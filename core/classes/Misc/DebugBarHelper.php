<?php

use DebugBar\DataCollector\ConfigCollector;
use DebugBar\DataCollector\MemoryCollector;
use DebugBar\DataCollector\PhpInfoCollector;
use DebugBar\DataCollector\RequestDataCollector;
use DebugBar\DataCollector\TimeDataCollector;
use DebugBar\DebugBar;
use DebugBar\DataCollector\PDO\PDOCollector;
use Junker\DebugBar\Bridge\SmartyCollector;

/**
 * Class to help integrate the PHPDebugBar with NamelessMC.
 *
 * @package NamelessMC\Misc
 * @author Aberdeener
 * @version 2.0.0-pr13
 * @license MIT
 */
class DebugBarHelper extends Instanceable {

    private ?DebugBar $_debugBar = null;

    /**
     * Enable the PHPDebugBar
     */
    public function enable(Smarty $smarty): void {
        $debugbar = new DebugBar();

        $debugbar->addCollector(new TimeDataCollector());

        $requestCollector = new RequestDataCollector();
        $requestCollector->useHtmlVarDumper();
        $debugbar->addCollector($requestCollector);

        $debugbar->addCollector(EventCollector::getInstance());

        $configCollector = new ConfigCollector();
        $configCollector->useHtmlVarDumper();
        $configCollector->setData(array_filter(Config::all(), static function ($key) {
            return $key !== 'mysql' && $key !== 'email' && $key !== 'authme';
        }, ARRAY_FILTER_USE_KEY));
        $debugbar->addCollector($configCollector);

        $pdoCollector = new PDOCollector(DB::getInstance()->getPDO());
        $pdoCollector->setRenderSqlWithParams(true, '`');
        $debugbar->addCollector($pdoCollector);

        $smartyCollector = new SmartyCollector($smarty);
        $smartyCollector->useHtmlVarDumper();
        $debugbar->addCollector($smartyCollector);

        $debugbar->addCollector(new PhpInfoCollector());
        $debugbar->addCollector(new MemoryCollector());

        $this->_debugBar = $debugbar;
    }

    public function getDebugBar(): ?DebugBar {
        return $this->_debugBar;
    }

}
