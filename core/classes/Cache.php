<?php
/* License
Copyright (c) 2012, Christian Metz
All rights reserved.

Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:

        * Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
        * Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.
        * Neither the name of the organisation nor the names of its contributors may be used to endorse or promote products derived from this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/

/**
 * Simple Cache class
 * API Documentation: https://github.com/cosenary/Simple-PHP-Cache
 *
 * @author Christian Metz
 * @since 22.12.2011
 * @copyright Christian Metz - MetzWeb Networks
 * @version 1.6-Nameless
 * @license BSD http://www.opensource.org/licenses/bsd-license.php
 *
 * Modified by Samerton for NamelessMC
 * https://github.com/NamelessMC/Nameless
 */

class Cache {

    /**
     * The path to the cache file folder
     *
     * @var string
     */
    private $_cachepath = 'cache/';

    /**
     * The name of the default cache file
     *
     * @var string
     */
    private $_cachename = 'default';

    /**
     * The cache file extension
     *
     * @var string
     */
    private $_extension = '.cache';

    /**
     * Default constructor
     *
     * @param string|array [optional] $config
     * @return void
     */
    public function __construct($config = null) {
        if (isset($config)) {
            if (is_string($config)) {
                $this->setCache($config);
            } else if (is_array($config)) {
                $this->setCache($config['name']);
                $this->setCachePath($config['path']);
                $this->setExtension($config['extension']);
            }
        }
    }

    /**
     * Check whether data accociated with a key
     *
     * @param string $key
     * @return boolean
     */
    public function isCached($key) {
        if ($this->_loadCache()) {
            $cachedData = $this->_loadCache();
            if (isset($cachedData[$key])) {
                $entry = $cachedData[$key];
                if ($entry && $this->_checkExpired($entry['time'], $entry['expire'])) {
                    return false;
                } else {
                    return isset($cachedData[$key]['data']);
                }
            }
        }
    }

    /**
     * Store data in the cache
     *
     * @param string $key
     * @param mixed $data
     * @param integer [optional] $expiration
     * @return object
     */
    public function store($key, $data, $expiration = 0) {
        $storeData = array(
            'time'   => time(),
            'expire' => $expiration,
            'data'   => serialize($data)
        );
        $dataArray = $this->_loadCache();
        if (is_array($dataArray)) {
            $dataArray[$key] = $storeData;
        } else {
            $dataArray = array($key => $storeData);
        }
        $cacheData = json_encode($dataArray);
        file_put_contents($this->getCacheDir(), $cacheData);
        return $this;
    }

    /**
     * Retrieve cached data by its key
     *
     * @param string $key
     * @param boolean [optional] $timestamp
     * @return string
     */
    public function retrieve($key, $timestamp = false) {
        $cachedData = $this->_loadCache();
        (!$timestamp) ? $type = 'data' : $type = 'time';
        if (!isset($cachedData[$key][$type])) return null;
        if (!$timestamp) {
            $entry = $cachedData[$key];
            if ($entry && $this->_checkExpired($entry['time'], $entry['expire'])) {
                return null;
            }
        }
        return unserialize($cachedData[$key][$type]);
    }

    /**
     * Retrieve all cached data
     *
     * @param boolean [optional] $meta
     * @return array
     */
    public function retrieveAll($meta = false) {
        if (!$meta) {
            $results = array();
            $cachedData = $this->_loadCache();
            if ($cachedData) {
                foreach ($cachedData as $k => $v) {
                    $results[$k] = unserialize($v['data']);
                }
            }
            return $results;
        } else {
            return $this->_loadCache();
        }
    }

    /**
     * Erase cached entry by its key
     *
     * @param string $key
     * @return object
     */
    public function erase($key) {
        $cacheData = $this->_loadCache();
        if (is_array($cacheData)) {
            if (isset($cacheData[$key])) {
                unset($cacheData[$key]);
                $cacheData = json_encode($cacheData);
                file_put_contents($this->getCacheDir(), $cacheData);
            } else {
                throw new Exception("Error: erase() - Key '{$key}' not found.");
            }
        }
        return $this;
    }

    /**
     * Erase all expired entries
     *
     * @return integer
     */
    public function eraseExpired() {
        $cacheData = $this->_loadCache();
        if (is_array($cacheData)) {
            $counter = 0;
            foreach ($cacheData as $key => $entry) {
                if ($this->_checkExpired($entry['time'], $entry['expire'])) {
                    unset($cacheData[$key]);
                    $counter++;
                }
            }
            if ($counter > 0) {
                $cacheData = json_encode($cacheData);
                file_put_contents($this->getCacheDir(), $cacheData);
            }
            return $counter;
        }
    }

    /**
     * Erase all cached entries
     *
     * @return object
     */
    public function eraseAll() {
        $cacheDir = $this->getCacheDir();
        if (file_exists($cacheDir)) {
            $cacheFile = fopen($cacheDir, 'w');
            fclose($cacheFile);
        }
        return $this;
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
        } else {
            return false;
        }
    }

    /**
     * Get the cache directory path
     *
     * @return string
     */
    public function getCacheDir() {
        if ($this->_checkCacheDir()) {
            $filename = $this->getCache();
            $filename = preg_replace('/[^0-9a-z\.\_\-]/i', '', strtolower($filename));
            return $this->getCachePath() . $this->_getHash($filename) . $this->getExtension();
        }
    }

    /**
     * Get the filename hash
     *
     * @return string
     */
    private function _getHash($filename) {
        return sha1($filename);
    }

    /**
     * Check whether a timestamp is still in the duration
     *
     * @param integer $timestamp
     * @param integer $expiration
     * @return boolean
     */
    private function _checkExpired($timestamp, $expiration) {
        $result = false;
        if ($expiration !== 0) {
            $timeDiff = time() - $timestamp;
            ($timeDiff > $expiration) ? $result = true : $result = false;
        }
        return $result;
    }

    /**
     * Check if a writable cache directory exists and if not create a new one
     *
     * @return boolean
     */
    private function _checkCacheDir() {
        if (!is_dir($this->getCachePath()) && !mkdir($this->getCachePath(), 0775, true)) {
            throw new Exception('Unable to create cache directory ' . $this->getCachePath());
        } elseif (!is_readable($this->getCachePath()) || !is_writable($this->getCachePath())) {
            if (!chmod($this->getCachePath(), 0775)) {
                throw new Exception('Your <b>' . $this->getCachePath() . '</b> directory must be readable and writeable. Check your file permissions.');
            }
        }
        return true;
    }

    /**
     * Cache path Setter
     *
     * @param string $path
     * @return object
     */
    public function setCachePath($path) {
        $this->_cachepath = $path;
        return $this;
    }

    /**
     * Cache path Getter
     *
     * @return string
     */
    public function getCachePath() {
        return $this->_cachepath;
    }

    /**
     * Cache name Setter
     *
     * @param string $name
     * @return object
     */
    public function setCache($name) {
        $this->_cachename = $name;
        return $this;
    }

    /**
     * Cache name Getter
     *
     * @return string
     */
    public function getCache() {
        return $this->_cachename;
    }

    /**
     * Cache file extension Setter
     *
     * @param string $ext
     * @return object
     */
    public function setExtension($ext) {
        $this->_extension = $ext;
        return $this;
    }

    /**
     * Cache file extension Getter
     *
     * @return string
     */
    public function getExtension() {
        return $this->_extension;
    }
}
