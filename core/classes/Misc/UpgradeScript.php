<?php
/**
 * Used for abstracting common tasks done during upgrades.
 *
 * @package NamelessMC\Misc
 * @author Aberdeener
 * @version 2.0.0-pr13
 * @license MIT
 */
abstract class UpgradeScript {

    protected Cache $_cache;
    protected Queries $_queries;

    protected string $_db_engine;
    protected string $_db_charset;

    public function __construct() {
        $this->_cache = new Cache(
            ['name' => 'nameless', 'extension' => '.cache', 'path' => ROOT_PATH . '/cache/']
        );

        $this->_queries = new Queries();

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

        $this->_db_engine = $db_engine;
        $this->_db_charset = $db_charset;
    }

    /**
     * Get instance of UpgradeScript for a specific NamelessMC version, null if it doesn't exist
     *
     * @param string $current_version Current NamelessMC version (ie: `2.0.0-pr12`, `2.0.0`)
     * @return UpgradeScript|null Instance of UpgradeScript from file
     */
    public static function get(string $current_version): ?UpgradeScript {
        $path = ROOT_PATH . '/core/includes/updates/' . str_replace('.', '', $current_version) . '.php';

        if (!file_exists($path)) {
            return null;
        }

        return require $path;
    }

    /**
     * Execute this UpgradeScript
     */
    abstract public function run(): void;

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
                $results[] = $query(DB::getInstance());
            } catch (Exception $exception) {
                $results[] = null;
                echo $exception->getMessage() . '<br />';
            }
        }

        return $results;
    }

    /**
     * Delete one or more folders or files in a path
     *
     * @param string $path Prefix path to append to each of the files in `$files` array
     * @param array $files Name of folders/files in `$path` to delete. Use `*` for all folders/files
     * @param bool $recursive Whether to recursively delete
     */
    protected function deleteFilesInPath(string $path, array $files, bool $recursive = false): void {
        if (in_array('*', $files)) {
            $files = scandir($path);
        }

        foreach ($files as $file) {

            if ($file[0] == '.') {
                continue;
            }

            if (file_exists($newFile = implode(DIRECTORY_SEPARATOR, [$path, $file]))) {

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

    /**
     * Delete a single folder or file
     *
     * @param string|array $paths Path to folder or file to delete
     */
    protected function deleteFiles($paths): void {
        foreach ((array) $paths as $path) {
            $path = ROOT_PATH . '/' . $path;
            if (!file_exists($path)) {
                echo "'$path' does not exist, cannot delete. <br />";
                continue;
            }

            if (!is_writable($path)) {
                echo "'$path' is not writable, cannot delete. <br />";
                return;
            }

            if (is_dir($path) && !rmdir($path)) {
                echo "Could not delete '$path', is it empty? <br />";
            }

            unlink($path);
        }
    }

    protected function setVersion(string $version): void {
        $this->_queries->update('settings', ['name', 'nameless_version'], [
            'value' => $version
        ]);

        $this->_queries->update('settings', ['name', 'version_update'], [
            'value' => 'false'
        ]);
    }
}
