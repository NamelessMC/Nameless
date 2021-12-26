<?php

use DebugBar\DebugBar;
use DebugBar\StandardDebugBar;
use DebugBar\DataCollector\PDO\PDOCollector;

class DebugBarHelper extends Instanceable {

    private DebugBar $debugBar;

    /**
     * Enable the PHPDebugBar + add the PDO Collector
     */
    public function enable() {
        $debugbar = new StandardDebugBar();

        $pdoCollector = new PDOCollector(DB::getInstance()->getPDO());
        $pdoCollector->setRenderSqlWithParams(true, '``');

        $debugbar->addCollector($pdoCollector);

        $this->debugBar = $debugbar;
    }

    public function getDebugBar(): DebugBar {
        return $this->debugBar;
    }


}
