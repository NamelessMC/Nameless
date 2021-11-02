<?php

abstract class UpgradeScript {

    protected Cache $cache;
    protected Queries $queries;

    protected string $db_engine;
    protected string $db_charset;

    /**
     * Execute this UpgradeScript
     */
    abstract public function run():  void;

    /**
     * Get instance of UpgradeScript for a specific NamelessMC version, null if it doesnt exist
     *
     * @param string $current_version Current NamelessMC version (ie: `2.0.0-pr12`, `2.0.0`)
     * @return UpgradeScript|null Instance of UpgradeScript from file
     */
    public static function get(string $current_version): ?UpgradeScript {
        $path = ROOT_PATH . '/core/includes/updates/' . str_replace('.', '', $current_version) . '.php';

        if (!file_exists($path)) {
            return null;
        }

        return (static function () use ($path) {
            return require $path;
        })();
    }

    public function __construct() {

        $this->cache = new Cache(
            ['name' => 'nameless', 'extension' => '.cache', 'path' => ROOT_PATH . '/cache/']
        );

        $this->queries = new Queries();

        try {
            $db_engine = Config::get('mysql/engine');
        } catch (Exception $e) {
            echo $e->getMessage() . '<br />';
        }
        if (!$db_engine || ($db_engine != 'MyISAM' && $db_engine != 'InnoDB')) {
            $db_engine = 'InnoDB';
        }

        try {
            $db_charset = Config::get('mysql/charset');
        } catch (Exception $e) {
            echo $e->getMessage() . '<br />';
        }
        if (!$db_charset || ($db_charset != 'utf8mb4' && $db_charset != 'latin1')) {
            $db_charset = 'latin1';
        }

        $this->db_engine = $db_engine;
        $this->db_charset = $db_charset;
    }

    /**
     * Run a single database query
     *
     * @param Closure $query Function which returns the query
     * @return array The result of the query, if any
     */
    protected function databaseQuery(Closure $query): array {
        return $this->databaseQueries([$query])[0];
    }

    /**
     * Run multiple queries
     *
     * @param Closure[] $queries Array of queries to execute one after another
     * @return array Results from queries in order
     */
    protected function databaseQueries(array $queries): array {
        $results = [];

        foreach ($queries as $query) {
            try {
                $results[] = $query();
            } catch (Exception $exception) {
                $results[] = null;
                echo $exception->getMessage() . '<br />';
            }
        }

        return [...$results];
    }

    /**
     * Delete a single folder or file
     *
     * @param string $path Path to folder or file to delete
     */
    protected function deleteFile(string $path) {
        if (!is_writeable($path)) {
            echo "'$path' is not writable, cannot delete. <br />";
            return;
        }

        if (is_dir($path)) {
            if (!rmdir($path)) {
                echo "Could not delete '$path', is it empty? <br />";
            }
        }

        unlink($path);
    }

    /**
     * Delete one or more folders or files in a path
     *
     * @param string $path Prefix path to append to each of the files in `$files` array
     * @param array $files Name of folders/files in `$path` to delete. Use `*` for all folders/files
     * @param bool $recursive Whether to recursively delete
     */
    protected function deleteFilesInPath(string $path, array $files, bool $recursive = false) {
        if (in_array('*', $files)) {
            $files = scandir($path);
        }

        foreach ($files as $file) {

            if ($file[0] == '.') {
                continue;
            }

            if (file_exists($newFile = join(DIRECTORY_SEPARATOR, [$path, $file]))) {

                if (is_dir($newFile)) {
                    if ($recursive) {
                        $this->deleteFilesInPath($newFile, ['*'], true);
                        $this->deleteFile($newFile);
                    }
                } else {
                    $this->deleteFile($newFile);
                }

            } else {
                echo "'$newFile' does not exist, cannot delete. <br />";
            }

        }
    }

    protected function setVersion(string $version) {
        $version_number_id = $this->queries->getWhere('settings', ['name', '=', 'nameless_version']);

        if (!count($version_number_id)) {
            $version_number_id = $this->queries->getWhere('settings', ['name', '=', 'version']);
        }

        $version_number_id = $version_number_id[0]->id;
        $this->queries->update('settings', $version_number_id, [
            'value' => $version
        ]);

        $version_update_id = $this->queries->getWhere('settings', ['name', '=', 'version_update']);
        $version_update_id = $version_update_id[0]->id;

        $this->queries->update('settings', $version_update_id, [
            'value' => 'false'
        ]);
    }
}