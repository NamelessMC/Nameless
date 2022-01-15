<?php

use DebugBar\DataCollector\ConfigCollector;
use DebugBar\DataCollector\MemoryCollector;
use DebugBar\DataCollector\PhpInfoCollector;
use DebugBar\DataCollector\RequestDataCollector;
use DebugBar\DataCollector\TimeDataCollector;
use DebugBar\DebugBar;
use DebugBar\DataCollector\PDO\PDOCollector;
use Junker\DebugBar\Bridge\SmartyCollector;

class DebugBarHelper extends Instanceable {

    private DebugBar $_debugBar;

    /**
     * Enable the PHPDebugBar + add the PDO Collector
     */
    public function enable(Smarty $smarty) {
        $debugbar = new DebugBar();

        $debugbar->addCollector(new TimeDataCollector());
        $debugbar->addCollector(new RequestDataCollector());

        $configCollector = new ConfigCollector();
        $configCollector->setData(array_filter($GLOBALS['config'], static function ($key) {
            return $key !== 'mysql' && $key !== 'allowedProxies';
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

    public function getDebugBar(): DebugBar {
        return $this->_debugBar;
    }

}
