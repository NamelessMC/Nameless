<?php

return new class extends UpgradeScript {

    public function run(): void {
        $this->runMigrations();

        // Some installations are missing the 'authme_db' setting row, add it now
        if (DB::getInstance()->get('settings', ['name', 'authme_db'])->count() == 0) {
            DB::getInstance()->query('INSERT INTO nl2_settings (id, name, value, module) VALUES (NULL, ?, ?, NULL)', [
                'authme_db',
                null,
            ]);
        }

        $this->setVersion('2.0.3');
    }
};
