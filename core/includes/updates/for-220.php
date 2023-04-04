<?php

// TODO: we should probably just use Phinx migrations for this :sweat_smile:
return new class extends UpgradeScript {

    public function run(): void {
        $this->runMigrations();

        // Add any missing default widgets

        $this->setVersion('2.2.0');
    }
};
