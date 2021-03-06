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

    public static function catchThrowable(Error $e) {
        // echo '<b>Error:</b> ' . $e->getMessage() . '<br>';

        // echo '<b>Frame #' . (count($e->getTrace()) + 1) . '</b> ' . $e->getFile() . ' - <b>Line: ' . $e->getLine() . '</b><br>';
        // $i = count($e->getTrace()) - 1;
        // foreach ($e->getTrace() as $frame) {
        //     echo '<b>Frame #' . ($i + 1) . '</b> ' . $frame['file'] . ' - <b>Line: ' . $frame['line'] . '</b><br>';
        //     $i--;
        // }

        $frames = array();
        $code = self::parseFile($e->getFile(), $e->getLine());
        $frames[] = [
            'number' => count($e->getTrace()) + 1,
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'code' => $code
        ];

        $i = count($e->getTrace()) - 1;
        foreach ($e->getTrace() as $frame) {
            $code = self::parseFile($frame['file'], $frame['line']);
            $frames[] = [
                'number' => $i + 1,
                'file'=> $frame['file'],
                'line' => $frame['line'],
                'code' => $code
            ];
            $i--;
        }

        $errstr = $e->getMessage();

        define('ERRORHANDLER', true);
        require_once(ROOT_PATH . DIRECTORY_SEPARATOR . 'error.php');
    }

    private static function parseFile($file, $error_line) {
        $return = '';
        $lines = file($file);
        $line_num = 1;

        foreach ($lines as $line) {
            if (($error_line - 20) <= $line_num && $line_num <= ($error_line + 20)) {
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

        } catch(Exception $e){
            // Unable to write to file, ignore for now
        }
    }

    // Log a custom error
    public static function logCustomError($contents){
        self::logError('other', $contents);
    }
}
