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
            $authme_db = (array) json_decode($authme_db);
            unset($authme_db['sync']);
            $authme_db['port'] = (int) $authme_db['port'];
            Config::set('authme', $authme_db);
            DB::getInstance()->delete('settings', ['name', 'authme_db']);
        }

        $this->setVersion('2.0.3');
    }
};
