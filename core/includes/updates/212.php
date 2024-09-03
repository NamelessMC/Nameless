<?php

return new class() extends UpgradeScript {
    public function run(): void
    {
        $this->runMigrations();

        PurgeInactiveUsers::schedule(new Language('core', 'en_UK'));

        $this->setVersion('2.2.0');
    }
};
