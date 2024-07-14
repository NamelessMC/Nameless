<?php

return new class() extends UpgradeScript {
    public function run(): void
    {
        $this->runMigrations();

        PurgeExpiredSessions::schedule(new Language('core', 'en_UK'));
        PurgeInactiveUsers::schedule(new Language('core', 'en_UK'));

        $this->setVersion('2.2.0');
    }
};
