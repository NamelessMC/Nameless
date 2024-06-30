<?php

use DebugBar\Bridge\NamespacedTwigProfileCollector;
use DebugBar\DataCollector\ConfigCollector;
use DebugBar\DataCollector\DataCollector;
use DebugBar\DataCollector\MemoryCollector;
use DebugBar\DataCollector\PDO\PDOCollector;
use DebugBar\DataCollector\PhpInfoCollector;
use DebugBar\DataCollector\RequestDataCollector;
use DebugBar\DataCollector\TimeDataCollector;
use DebugBar\DebugBar;
use Junker\DebugBar\Bridge\SmartyCollector;
use Twig\Environment;
use Twig\Extension\ProfilerExtension;
use Twig\Profiler\Profile;

/**
 * Class to help integrate the PHPDebugBar with NamelessMC.
 *
 * @package NamelessMC\Misc
 * @author Aberdeener
 * @version 2.2.0
 * @license MIT
 */
class DebugBarHelper extends Instanceable
{
    private ?DebugBar $_debugBar = null;

    /**
     * Enable the PHPDebugBar.
     */
    public function enable(): void
    {
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

        $debugbar->addCollector(new PhpInfoCollector());
        $debugbar->addCollector(new MemoryCollector());

        $this->_debugBar = $debugbar;
    }

    public function addCollector(DataCollector $collector): void
    {
        $this->_debugBar->addCollector($collector);
    }

    public function addSmartyCollector(Smarty $smarty): void
    {
        $smartyCollector = new SmartyCollector($smarty);
        $smartyCollector->useHtmlVarDumper();
        $this->addCollector($smartyCollector);
    }

    public function addTwigCollector(Environment $twig, Profile $profile): void
    {
        $twig->addExtension(new ProfilerExtension($profile));
        $this->addCollector(new NamespacedTwigProfileCollector($profile));
    }

    public function getDebugBar(): ?DebugBar
    {
        return $this->_debugBar;
    }
}
