<?php
declare(strict_types=1);

/**
 * TODO: Description
 *
 * @package NamelessMC\Includes
 * @author Samerton
 * @version 2.0.2
 * @license MIT
 */
return new class extends UpgradeScript {

    /**
     * Execute this UpgradeScript.
     */
    public function run(): void {
        $this->runMigrations();
        $this->setVersion('2.0.2');
    }
};
