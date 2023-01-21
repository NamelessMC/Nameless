<?php

return new class extends UpgradeScript {

    public function run(): void {
        $this->runMigrations();

        // Move 'authme_db' json row in settings table to 'authme' section in config file
        $authme_db = null;
        $result = DB::getInstance()->get('settings', ['name', 'authme_db']);
        if ($result->count()) {
            $authme_db = $result->first()->value;
        }
        if ($authme_db !== null) {
            Config::set('authme', (array) json_decode($authme_db));
            DB::getInstance()->delete('settings', ['name', 'authme_db']);
        }

        $this->setVersion('2.0.3');
    }
};
