<?php

return new class extends UpgradeScript {

    public function run(): void {
        $this->runMigrations();

        Config::set('core.encoded_content_compat', true);

        $this->setVersion('2.1.0');
    }
};
