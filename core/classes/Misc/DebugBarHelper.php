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
     * Enable the PHPDebugBar + add the PDO Collector
     */
    public function enable(Smarty $smarty): void {
        $debugbar = new DebugBar();

        $debugbar->addCollector(new TimeDataCollector());
        $debugbar->addCollector(new RequestDataCollector());

        $configCollector = new ConfigCollector();
        $configCollector->setData(array_filter($GLOBALS['config'], static function ($key) {
            return $key !== 'mysql';
        }, ARRAY_FILTER_USE_KEY));
        $debugbar->addCollector($configCollector);

        $pdoCollector = new PDOCollector(DB::getInstance()->getPDO());
        $pdoCollector->setRenderSqlWithParams(true, '`');
        $debugbar->addCollector($pdoCollector);

        $debugbar->addCollector(new SmartyCollector($smarty));
        $debugbar->addCollector(new PhpInfoCollector());
        $debugbar->addCollector(new MemoryCollector());

        $this->_debugBar = $debugbar;
    }

    public function getDebugBar(): ?DebugBar {
        return $this->_debugBar;
    }

}
