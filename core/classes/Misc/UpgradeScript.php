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

    public function __construct() {
        $this->_cache = new Cache(
            ['name' => 'nameless', 'extension' => '.cache', 'path' => ROOT_PATH . '/cache/']
        );
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
     * Logs a message to the screen and the warning-log.log file.
     *
     * @param string $message Message to log
     */
    protected function log(string $message): void {
        echo $message . '<br/>';
        ErrorHandler::logWarning('UPGRADING EXCEPTION: ' . $message);
    }

    /**
     * Run a single database query
     *
     * @param Closure $query Function which returns the query
     * @return mixed The result of the closure, if any
     */
    protected function databaseQuery(Closure $query) {
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
                $this->log($exception->getMessage());
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
                        $this->deleteFiles($newFile);
                    }
                } else {
                    $this->deleteFiles($newFile);
                }

            } else {
                $this->log("'$newFile' does not exist, cannot delete.");
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
                $this->log("'$path' does not exist, cannot delete.");
                continue;
            }

            if (!is_writable($path)) {
                $this->log("'$path' is not writable, cannot delete.");
                return;
            }

            if (is_dir($path) && !rmdir($path)) {
                $this->log("Could not delete '$path', is it empty?");
            }

            unlink($path);
        }
    }

    /**
     * Execute any pending database migrations.
     */
    protected function runMigrations(): void {
        PhinxAdapter::migrate();
    }

    /**
     * Update the version of this NamelessMC website in the database.
     *
     * @param string $version Version to set
     */
    protected function setVersion(string $version): void {
        Util::setSetting('nameless_version', $version);
        Util::setSetting('version_update', null);
    }
}
