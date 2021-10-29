<?php

return new class extends UpgradeScript {

    public function run(): void {

        $this->deleteFilesInPath(
            ROOT_PATH . '/core/classes',
            ['*']
        );

        // do stuff with cache
        $this->cache->eraseAll();

        // do stuff in db using DB class or queries
        // note: can also use db_charset and db_engine
        [, $users] = $this->databaseQueries([
            fn() => $this->queries->alterTable('idk', 'example', $this->db_charset . $this->db_engine),
            fn() => DB::getInstance()->get('users', ['id', '<>', 0])->results(),
        ]);

        var_dump($users);

        // do stuff with logged in user
        echo $this->user->getIP();

        $this->setVersion('2.1.0');
    }

};
