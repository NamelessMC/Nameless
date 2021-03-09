<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Error handler class
 */

class ErrorHandler {

    /*
     * Defined for easy changing.
     * This constant indicates how many LOC from each frame's PHP file to show before and after the highlighted line
     */
    private const LINE_BUFFER = 20;

    public static function catchException($e) {

        $frames = array();

        // Most recent frame is not included in getTrace(), so deal with it individually
        $lines = file($e->getFile());
        $code = self::parseFile($lines, $e->getLine());
        $frames[] = [
            'number' => count($e->getTrace()) + 1,
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'start_line' => count($lines) >= self::LINE_BUFFER ? ($e->getLine() - self::LINE_BUFFER) : 1,
            'highlight_line' => count($lines) >= self::LINE_BUFFER ? (self::LINE_BUFFER + 1) : $e->getLine(),
            'code' => $code
        ];

        // Loop all frames in the exception trace & get relevent information
        $i = count($e->getTrace());
        foreach ($e->getTrace() as $frame) {

            $lines = file($frame['file']);

            $code = self::parseFile($lines, $frame['line']);
            $frames[] = [
                'number' => $i,
                'file'=> $frame['file'],
                'line' => $frame['line'],
                'start_line' => count($lines) >= self::LINE_BUFFER ? ($frame['line'] - self::LINE_BUFFER) : 1,
                'highlight_line' => count($lines) >= self::LINE_BUFFER ? (self::LINE_BUFFER + 1) : $frame['line'],
                'code' => $code
            ];
            
            $i--;
        }

        define('ERRORHANDLER', true);
        require_once(ROOT_PATH . DIRECTORY_SEPARATOR . 'error.php');
        die();
    }

    private static function parseFile($lines, $error_line) {

        $return = '';

        if ($lines == false || count($lines) < 1) {
            return $return;
        }

        $line_num = 1;

        foreach ($lines as $line) {
            if (($error_line - self::LINE_BUFFER) <= $line_num && $line_num <= ($error_line + self::LINE_BUFFER)) {
                $return .= Output::getClean($line);
            }

            $line_num++;
        }

        return $return;
    }

    public static function catchError($errno, $errstr, $errfile, $errline) {
        if(!(error_reporting() & $errno))
            return false;

        switch($errno){
            case E_USER_ERROR:
                define('ERRORHANDLER', true);
                require_once(ROOT_PATH . DIRECTORY_SEPARATOR . 'error.php');
                self::logError('fatal', '[' . date('Y-m-d, H:i:s') . '] ' . $errfile . '(' . $errline . ') ' . $errno . ': ' . $errstr);
                die(1);
                break;

            case E_USER_WARNING:
                self::logError('warning', '[' . date('Y-m-d, H:i:s') . '] ' . $errfile . '(' . $errline . ') ' . $errno . ': ' . $errstr);
                break;

            case E_USER_NOTICE:
                self::logError('notice', '[' . date('Y-m-d, H:i:s') . '] ' . $errfile . '(' . $errline . ') ' . $errno . ': ' . $errstr);
                break;

            default:
                self::logError('other', '[' . date('Y-m-d, H:i:s') . '] ' . $errfile . '(' . $errline . ') ' . $errno . ': ' . $errstr);
                break;
        }

        return true;
    }

    public static function catchFatalError() {
        $error = error_get_last();

        if ($error == null) return;

        if ($error['type'] === E_ERROR) {
            $errstr = $error['message'];
            $errfile = $error['file'];
            $errline = $error['line'];

            define('ERRORHANDLER', true);
            require_once(ROOT_PATH . DIRECTORY_SEPARATOR . 'error.php');
            self::logError('fatal', '[' . date('Y-m-d, H:i:s') . '] ' . $errfile . '(' . $errline . '): ' . $errstr);
            die(1);
        }
    }

    private static function logError($type, $contents) {
        try {
            if(!is_dir(join(DIRECTORY_SEPARATOR, array(ROOT_PATH, 'cache', 'logs')))){
                if(is_writable(ROOT_PATH . DIRECTORY_SEPARATOR . 'cache')) {
                    mkdir(ROOT_PATH . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'logs');
                    $dir_exists = true;
                }
            } else
                $dir_exists = true;

            if(isset($dir_exists))
                file_put_contents(join(DIRECTORY_SEPARATOR, array(ROOT_PATH, 'cache', 'logs', $type . '-log.log')), $contents . PHP_EOL, FILE_APPEND);

        } catch (Exception $e) {
            // Unable to write to file, ignore for now
        }
    }

    // Log a custom error
    public static function logCustomError($contents){
        self::logError('other', $contents);
    }
}
