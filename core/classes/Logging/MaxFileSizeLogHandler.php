<?php

/**
 * NamelessMC max file size log handler class for Monolog.
 *
 * @package NamelessMC\Core
 * @author Samerton
 * @version 2.3.0
 * @license MIT
 */

use Monolog\Level;
use Monolog\Utils;
use Monolog\LogRecord;
use Monolog\Handler\StreamHandler;

class MaxFileSizeLogHandler extends StreamHandler
{
    protected string $fileName;
    protected int $maxFiles;
    protected int $maxFileSize;
    protected bool|null $mustRotate = null;
    protected string $filenameFormat;
    protected string $dateFormat;

    /**
     * @param string           $fileName     Base path to file
     * @param int|string|Level $level       Level of logging this handler should handle - default debug
     * @param int              $maxFiles    The number of files to keep, 0 is no limit - default 3
     * @param bool             $bubble      Whether to "bubble" the log onto the next handler - default true
     * @param int              $maxFileSize Maximum file size before new file is created in bytes - default 500kb
     */
    public function __construct(
        string $fileName,
        int|string|Level $level = Level::Debug,
        int $maxFiles = 3,
        bool $bubble = true,
        int $maxFileSize = 500000,
    ) {
        $this->fileName = Utils::canonicalizePath($fileName);
        $this->maxFiles = $maxFiles;
        $this->maxFileSize = $maxFileSize;

        parent::__construct($this->getNewFileName(), $level, $bubble);
    }

    public function close(): void
    {
        parent::close();

        if ($this->mustRotate === true) {
            $this->rotate();
        }
    }

    protected function write(LogRecord $record): void
    {
        // If the log is new then we need to rotate such that the file will exist
        if ($this->mustRotate === null) {
            $this->mustRotate = $this->url === null || !file_exists($this->url);
        }

        // Rotate now if the file size is too big
        if (file_exists($this->url) && filesize($this->url) > $this->maxFileSize) {
            $this->mustRotate = true;

            $this->close();
        }

        parent::write($record);

        if ($this->mustRotate === true) {
            $this->close();
        }
    }

    /**
     * Rotates the files.
     */
    protected function rotate(): void
    {
        $this->url = $this->getNewFileName();

        $this->mustRotate = false;

        if ($this->maxFiles === 0) {
            return;
        }

        $logFiles = glob($this->getGlobPattern());

        if ($logFiles === false) {
            return;
        }

        // If we have reached the maximum number of allowed files, delete older files
        if ($this->maxFiles < count($logFiles)) {
            usort($logFiles, function ($a, $b) {
                if (filemtime($a) < filemtime($b)) {
                    return 1;
                }

                return -1;
            });

            foreach (array_slice($logFiles, $this->maxFiles) as $file) {
                if (is_writable($file)) {
                    unlink($file);
                }
            }
        }

        // Rename the log file to -old-<time> format so we can open up a new file
        if (file_exists($this->url) && filesize($this->url) > $this->maxFileSize) {
            $time = date('His');
            $oldFile = $this->getNewFileName($time);
            rename($this->url, $oldFile);
        }
    }

    private function getNewFileName(string $time = ''): string
    {
        $fileInfo = pathinfo($this->fileName);
        $dirName = $fileInfo['dirname'];
        $fileExtension = $fileInfo['extension'];
        $fileName = $fileInfo['filename'];

        $date = date('Ymd');
        $timeSuffix = $time ? "-old-$time" : '';

        return implode(DIRECTORY_SEPARATOR, [$dirName, "$fileName-$date$timeSuffix.$fileExtension"]);
    }

    private function getGlobPattern(): string
    {
        $fileInfo = pathinfo($this->fileName);
        $dirName = $fileInfo['dirname'];
        $fileExtension = $fileInfo['extension'];
        $fileName = $fileInfo['filename'];

        return implode(DIRECTORY_SEPARATOR, [$dirName, "$fileName-*.$fileExtension"]);
    }
}
