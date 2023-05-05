<?php

class PhinxAdapter {

    /**
     * Checks the number of existing migration files compared to executed migrations in the database.
     * Alternatively we could check the output of a Phinx command, but that takes ~8x as long to execute.
     *
     * @throws RuntimeException If these numbers don't match.
     */
    public static function ensureUpToDate(): void {
        $migration_files = array_map(
            static function ($file_name) {
                [$version, $migration_name] = explode('_', $file_name, 2);
                $migration_name = str_replace(['.php', '_'], '', ucwords($migration_name, '_'));
                return $version . '_' . $migration_name;
            },
            array_filter(scandir(__DIR__ . '/../../migrations'), static function ($file_name) {
                // Pattern that matches Phinx migration file names (eg: 20230403000000_create_stroopwafel_table.php)
                return preg_match('/^\d{14}_\w+\.php$/', $file_name);
            }),
        );

        $migration_database_entries = array_map(static function ($row) {
            return $row->version . '_' . $row->migration_name;
        }, DB::getInstance()->query('SELECT version, migration_name FROM nl2_phinxlog')->results());

        $missing = array_diff($migration_files, $migration_database_entries);
        $extra = array_diff($migration_database_entries, $migration_files);

        // Likely a pull from the repo dev branch or migrations
        // weren't run during an upgrade script.
        if (($missing_count = count($missing)) > 0) {
            echo "There are $missing_count migrations files which have not been executed:" . '<br>';
            foreach ($missing as $missing_migration) {
                echo " - $missing_migration" . '<br>';
            }
        }

        // Something went wonky, either they've deleted migration files,
        // or they've added stuff to the nl2_phinxlog table.
        if (($extra_count = count($extra)) > 0) {
            if ($missing_count > 0) {
                echo '<br>';
            }
            echo "There are $extra_count executed migrations which do not have migration files associated:" . '<br>';
            foreach ($extra as $extra_migration) {
                echo " - $extra_migration" . '<br>';
            }
        }

        if ($missing_count > 0 || $extra_count > 0) {
            die();
        }
    }

    /**
     * Runs any pending migrations. Used for installation and upgrades. Resource heavy, only call when needed.
     * Logs output of Phinx to other-log.log file
     *
     * @return string Output of the migration command from Phinx as if it was executed in the console.
     */
    public static function migrate(): string {
        $output = (new Phinx\Wrapper\TextWrapper(
            new Phinx\Console\PhinxApplication(),
            [
                'configuration' => __DIR__ . '/../../migrations/phinx.php',
            ]
        ))->getMigrate();

        ErrorHandler::logCustomError($output);

        return $output;
    }

}
