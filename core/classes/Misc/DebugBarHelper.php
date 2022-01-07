<?php

use DebugBar\DataCollector\ConfigCollector;
use DebugBar\DataCollector\ExceptionsCollector;
use DebugBar\DataCollector\MessagesCollector;
use DebugBar\DataCollector\RequestDataCollector;
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

        $messagesCollector = new MessagesCollector();
        $debugbar->addCollector($messagesCollector);

        $exceptionCollector = new ExceptionsCollector();
        $debugbar->addCollector($exceptionCollector);

        $requestCollector = new RequestDataCollector();
        $debugbar->addCollector($requestCollector);

        $configCollector = new ConfigCollector();
        $configCollector->setData(array_filter($GLOBALS['config'], static function ($key) {
            return $key !== 'mysql' && $key !== 'allowedProxies';
        }, ARRAY_FILTER_USE_KEY));
        $debugbar->addCollector($configCollector);

        $pdoCollector = new PDOCollector(DB::getInstance()->getPDO());
        $pdoCollector->setRenderSqlWithParams(true, '`');
        $debugbar->addCollector($pdoCollector);

        $smartyCollector = new SmartyCollector($smarty);
        $debugbar->addCollector($smartyCollector);

        $this->_debugBar = $debugbar;
    }

    public function getDebugBar(): DebugBar {
        return $this->_debugBar;
    }

}
