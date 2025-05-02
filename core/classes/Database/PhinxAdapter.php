<?php

class PhinxAdapter
{
    /**
     * Checks the number of existing migration files compared to executed migrations in the database.
     * Alternatively we could check the output of a Phinx command, but that takes ~8x as long to execute.
     *
     * TODO: return type as array|never (8.1)
     *
     * @param string  $module        Module name
     * @param ?string $migrationDir  Migration directory
     * @param bool    $returnResults If true the results will be returned - otherwise script execution is ended
     *
     * @return array|void
     */
    public static function ensureUpToDate(
        string $module,
        ?string $migrationDir = null,
        bool $returnResults = false
    ) {
        $module = strtolower($module);

        if ($module === 'core') {
            $table = 'nl2_phinxlog';
        } else {
            $module = preg_replace('/[^a-zA-Z]+$/', '', $module);
            $table = "nl2_phinxlog_$module";
        }

        if (!$migrationDir) {
            $migrationDir = __DIR__ . '/../../migrations';
        }

        $migration_files = array_map(
            static function ($file_name) {
                [$version, $migration_name] = explode('_', $file_name, 2);
                $migration_name = str_replace(['.php', '_'], '', ucwords($migration_name, '_'));

                return $version . '_' . $migration_name;
            },
            array_filter(scandir($migrationDir), static function ($file_name) {
                // Pattern that matches Phinx migration file names (eg: 20230403000000_create_stroopwafel_table.php)
                return preg_match('/^\d{14}_\w+\.php$/', $file_name);
            }),
        );

        $migration_database_entries = array_map(static function ($row) {
            return $row->version . '_' . $row->migration_name;
        }, DB::getInstance()->query("SELECT version, migration_name FROM $table")->results());

        $missing = array_diff($migration_files, $migration_database_entries);
        $extra = array_diff($migration_database_entries, $migration_files);

        if ($returnResults) {
            return [
                'missing' => count($missing),
                'extra' => count($extra),
            ];
        }

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
            die;
        }
    }

    /**
     * Runs any pending migrations. Used for installation and upgrades. Resource heavy, only call when needed.
     * Logs output of Phinx to other-log.log file.
     *
     * @param string  $module       Module name
     * @param ?string $migrationDir Migration directory to use
     *
     * @return string Output of the migration command from Phinx as if it was executed in the console.
     */
    public static function migrate(
        string $module,
        ?string $migrationDir = null
    ): string {
        $module = strtolower($module);

        if ($module === 'core') {
            $table = 'nl2_phinxlog';
        } else {
            $module = preg_replace('/[^a-zA-Z]+$/', '', $module);
            $table = "nl2_phinxlog_$module";
        }

        define('PHINX_DB_TABLE', $table);
        define('PHINX_MIGRATIONS_DIR', $migrationDir ?? (__DIR__ . '/../../migrations'));

        $output = (new Phinx\Wrapper\TextWrapper(
            new Phinx\Console\PhinxApplication(),
            [
                'configuration' => __DIR__ . '/../../migrations/phinx.php',
            ]
        ))->getMigrate();

        ErrorHandler::logCustomError($output);

        return $output;
    }

    /**
     * Rolls back migrations
     * Logs output of Phinx to other-log.log file.
     *
     * @param string $module       Module name
     * @param string $migrationDir Migration directory to use
     * @param int    $since        Version of earliest migration to rollback, default 0 for all
     *
     * @throws Exception If unable to rollback
     * @return string    Output of the migration command from Phinx as if it was executed in the console.
     */
    public static function rollback(
        string $module,
        string $migrationDir,
        int $since = 0
    ): string {
        $module = strtolower($module);

        if ($module === 'core') {
            $table = 'nl2_phinxlog';
        } else {
            $module = preg_replace('/[^a-zA-Z]+$/', '', $module);
            $table = "nl2_phinxlog_$module";
        }

        if (
            $table === 'nl2_phinxlog' ||
            strtolower($migrationDir) === (__DIR__ . '/../../migrations')
        ) {
            throw new Exception('Cannot rollback core migrations');
        }

        define('PHINX_DB_TABLE', $table);
        define('PHINX_MIGRATIONS_DIR', $migrationDir);

        $output = (new Phinx\Wrapper\TextWrapper(
            new Phinx\Console\PhinxApplication(),
            [
                'configuration' => __DIR__ . '/../../migrations/phinx.php',
            ]
        ))->getRollback(null, $since);

        ErrorHandler::logCustomError($output);

        return $output;
    }
}
