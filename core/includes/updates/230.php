<?php

return new class() extends UpgradeScript {
    public function run(): void
    {
        $this->runMigrations();

        ConvertProfilePosts::schedule();

        $this->setVersion('2.3.0');
    }
};
