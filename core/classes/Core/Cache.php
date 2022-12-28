<?php
declare(strict_types=1);

/**
 * Handles caching for NamelessMC.
 *
 * @package NamelessMC\Core
 * @author Christian Metz
 * @version 1.6-Nameless
 * @license BSD http://www.opensource.org/licenses/bsd-license.php
 */
class Cache {

    /**
     * The path to the cache file folder
     */
    private string $_cache_path = 'cache/';

    /**
     * The name of the default cache file
     */
    private string $_cache_name = 'default';

    /**
     * The cache file extension
     */
    private string $_extension = '.cache';

    /**
     * Create a new Cache instance
     *
     * @param string|array<string, mixed> $config (optional)
     * @return void
     */
    public function __construct($config = null) {
        if (isset($config)) {
            if (is_string($config)) {
                $this->setCacheName($config);
            } else {
                $this->setCacheName($config['name']);
                $this->setCachePath($config['path']);
                $this->setExtension($config['extension']);
            }
        }
    }

    /**
     * Cache name Setter
     *
     * @param string $name Name of cache file to use
     * @return Cache
     */
    public function setCacheName(string $name): Cache {
        $this->_cache_name = $name;
        return $this;
    }

    /**
     * Check if data is associated with a key in the cache
     *
     * @param string $key The key to check
     * @return bool
     */
    public function hasCashedData(string $key): bool {
        if ($this->_loadCache()) {
            $cachedData = $this->_loadCache();
            if (isset($cachedData[$key])) {
                $entry = $cachedData[$key];
                if ($entry && $this->_checkExpired($entry['time'], $entry['expire'])) {
                    return false;
                }

                return isset($cachedData[$key]['data']);
            }
        }

        return false;
    }

    /**
     * Load appointed cache
     *
     * @return mixed
     */
    private function _loadCache() {
        if (file_exists($this->getCacheDir())) {
            $file = file_get_contents($this->getCacheDir());
            return json_decode($file, true);
        }

        return false;
    }

    /**
     * Get the cache directory path
     *
     * @return string
     */
    public function getCacheDir(): string {
        if ($this->_checkCacheDir()) {
            $filename = $this->getCache();
            $filename = preg_replace('/[^0-9a-z\-]/i', '', strtolower($filename));
            return $this->getCachePath() . $this->_getHash($filename) . $this->getExtension();
        }

        return '';
    }

    /**
     * Check if a writable cache directory exists and if not create a new one
     *
     * @return bool
     */
    private function _checkCacheDir(): bool {
        if (!is_dir($this->getCachePath()) && !mkdir($concurrentDirectory = $this->getCachePath(), 0775, true) && !is_dir($concurrentDirectory)) {
            throw new RuntimeException('Unable to create cache directory ' . $this->getCachePath());
        }

        if (!is_readable($this->getCachePath()) || !is_writable($this->getCachePath())) {
            if (!chmod($this->getCachePath(), 0775)) {
                throw new RuntimeException('Your <b>' . $this->getCachePath() . '</b> directory must be readable and writeable. Check your file permissions.');
            }
        }
        return true;
    }

    /**
     * Cache path Getter
     *
     * @return string The path to the cache file folder
     */
    public function getCachePath(): string {
        return $this->_cache_path;
    }

    /**
     * Cache path Setter
     *
     * @param string $path
     * @return Cache
     */
    public function setCachePath(string $path): Cache {
        $this->_cache_path = $path;
        return $this;
    }

    /**
     * Cache name Getter
     *
     * @return string Cache name
     */
    public function getCache(): string {
        return $this->_cache_name;
    }

    /**
     * Get the filename hash
     *
     * @param string $filename
     * @return string The hashed filename
     */
    private function _getHash(string $filename): string {
        return sha1($filename);
    }

    /**
     * Cache file extension Getter
     *
     * @return string Cache file extension
     */
    public function getExtension(): string {
        return $this->_extension;
    }

    /**
     * Cache file extension Setter
     *
     * @param string $ext Extension to use
     * @return Cache
     */
    public function setExtension(string $ext): Cache {
        $this->_extension = $ext;
        return $this;
    }

    /**
     * Check whether a timestamp is still in the duration
     *
     * @param int $timestamp Timestamp to check
     * @param int $expiration Duration to check
     * @return bool True if still in duration
     */
    private function _checkExpired(int $timestamp, int $expiration): bool {
        $result = false;
        if ($expiration !== 0) {
            $timeDiff = time() - $timestamp;
            $result = $timeDiff > $expiration;
        }
        return $result;
    }

    /**
     * Store data in the cache
     *
     * @param string $key Key to store data under
     * @param mixed $data Data to store
     * @param int $expiration Expiration time in seconds
     *
     * @return Cache
     */
    public function store(string $key, $data, int $expiration = 0): Cache {
        $storeData = [
            'time' => time(),
            'expire' => $expiration,
            'data' => serialize($data)
        ];
        $dataArray = $this->_loadCache();
        if (is_array($dataArray)) {
            $dataArray[$key] = $storeData;
        } else {
            $dataArray = [$key => $storeData];
        }
        $cacheData = json_encode($dataArray);
        file_put_contents($this->getCacheDir(), $cacheData);
        return $this;
    }

    /**
     * Retrieve cached data by its key
     *
     * @param string $key The key to retrieve
     * @param bool $timestamp Whether to check if the cache is expired
     * @return null|mixed The cached data or null if not found/expired
     */
    public function retrieve(string $key, bool $timestamp = false) {
        $cacheData = $this->_loadCache();
        if (!is_array($cacheData) || !isset($cacheData[$key])) {
            return null;
        }

        $entry = $cacheData[$key];
        if (!$timestamp && $this->_checkExpired($entry['time'], $entry['expire'])) {
            return null;
        }

        return $timestamp ? $entry['time'] : unserialize($entry['data']);
    }

    /**
     * Retrieve all cached data
     *
     * @param bool $meta (optional)
     * @return array The cached data
     */
    public function retrieveAll(bool $meta = false): array {
        if ($meta) {
            return $this->_loadCache();
        }

        $results = [];
        $cachedData = $this->_loadCache();
        if ($cachedData) {
            foreach ($cachedData as $k => $v) {
                $results[$k] = unserialize($v['data']);
            }
        }
        return $results;
    }

    /**
     * Erase cached entry by its key
     *
     * @param string $key The key to erase
     * @return Cache
     * @throws RuntimeException If the key is not found in the cache
     */
    public function erase(string $key): Cache {
        $cacheData = $this->_loadCache();
        if (!is_array($cacheData)) {
            return $this;
        }

        if (!isset($cacheData[$key])) {
            throw new RuntimeException("Error: erase() - Key '$key' not found.");
        }

        unset($cacheData[$key]);
        file_put_contents($this->getCacheDir(), json_encode($cacheData));

        return $this;
    }

    /**
     * Erase all expired entries
     *
     * @return int Number of entries erased
     */
    public function eraseExpired(): int {
        $cacheData = $this->_loadCache();
        if (!is_array($cacheData)) {
            return -1;
        }

        $counter = 0;
        foreach ($cacheData as $key => $entry) {
            if ($this->_checkExpired($entry['time'], $entry['expire'])) {
                unset($cacheData[$key]);
                $counter++;
            }
        }

        if ($counter > 0) {
            file_put_contents($this->getCacheDir(), json_encode($cacheData));
        }

        return $counter;
    }

    /**
     * Erase all cached entries
     *
     * @return Cache
     */
    public function eraseAll(): Cache {
        $cacheDir = $this->getCacheDir();

        if (file_exists($cacheDir)) {
            $handle = fopen($cacheDir, 'wb');
            fclose($handle);
        }

        return $this;
    }
}
