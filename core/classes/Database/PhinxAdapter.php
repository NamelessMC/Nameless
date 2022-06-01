<?php

class PhinxAdapter {

    /**
     * Checks the number of existing migration files compared to executed migrations in the database.
     * Alternatively we could check the output of a Phinx command, but that takes ~8x as long to execute.
     *
     * @throws RuntimeException If these numbers don't match.
     */
    public static function ensureUpToDate(): void {
        $migration_files = count(scandir(__DIR__ . '/../../migrations')) - 3; // -3 because of . and .. and phinx.php
        $migration_database_entries = DB::getInstance()->query('SELECT COUNT(*) AS count FROM nl2_phinxlog')->first()->count;

        if ($migration_files == $migration_database_entries) {
            return;
        }

        // Likely a pull from the repo dev branch or migrations
        // weren't run during an upgrade script.
        if (($diff = abs($migration_files - $migration_database_entries)) > 0) {
            throw new RuntimeException("There are {$diff} database migrations pending.");
        }

        // Something went wonky, either they've deleted migration files,
        // or they've added stuff to the nl2_phinxlog table.
        throw new RuntimeException("Inconsistent number of migration database entries ({$migration_database_entries}) and migration files ({$migration_files}).");
    }

    /**
     * Runs any pending migrations. Used for installation and upgrades. Resource heavy, only call when needed.
     *
     * @return string Output of the migration command from Phinx as if it was executed in the console.
     */
    public static function migrate(): string {
        return (new Phinx\Wrapper\TextWrapper(
            new Phinx\Console\PhinxApplication(),
            [
                'configuration' => __DIR__ . '/../../migrations/phinx.php',
            ]
        ))->getMigrate();
    }

}
