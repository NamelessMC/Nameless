<?php
/*
 *	Made by Samerton
 *  Additions by Aberdeener
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr9
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

    /*
     * Used to neatly display exceptions and the trace/frames leading up to it.
     * If this is called manually, the error_string, error_file and error_line must be manually provided,
     * and a single trace frame will be generated for it.
     */
    public static function catchException($exception, $error_string = null, $error_file = null, $error_line = null) {

        // Define variables based on if a Throwable was caught by the compiler, or if this was called manually
        $error_string = is_null($exception) ? $error_string : $exception->getMessage();
        $error_file = is_null($exception) ? $error_file : $exception->getFile();
        $error_line = is_null($exception) ? intval($error_line) : $exception->getLine();

        // Create a log entry for viewing in staffcp
        self::logError('fatal', '[' . date('Y-m-d, H:i:s') . '] ' . $error_file . '(' . $error_line . '): ' . $error_string);

        $frames = array();

        // Most recent frame is not included in getTrace(), so deal with it individually
        $lines = file($error_file);
        $code = self::parseFile($lines, $error_line);
        $frames[] = [
            'number' => is_null($exception) ? 1 : count($exception->getTrace()) + 1,
            'file' => $error_file,
            'line' => $error_line,
            'start_line' => count($lines) >= self::LINE_BUFFER ? ($error_line - self::LINE_BUFFER) : 1,
            'highlight_line' => count($lines) >= self::LINE_BUFFER ? (self::LINE_BUFFER + 1) : $error_line,
            'code' => $code
        ];

        // Loop all frames in the exception trace & get relevent information
        if ($exception != null) {
            $i = count($exception->getTrace());
            foreach ($exception->getTrace() as $frame) {

                $lines = file($frame['file']);

                $code = self::parseFile($lines, $frame['line']);
                $frames[] = [
                    'number' => $i,
                    'file' => $frame['file'],
                    'line' => $frame['line'],
                    'start_line' => count($lines) >= self::LINE_BUFFER ? ($frame['line'] - self::LINE_BUFFER) : 1,
                    'highlight_line' => count($lines) >= self::LINE_BUFFER ? (self::LINE_BUFFER + 1) : $frame['line'],
                    'code' => $code
                ];

                $i--;
            }
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

        if(!(error_reporting() & $errno)) {
            return false;
        }

        switch($errno) {
            case E_USER_ERROR:
                // Pass execution to new error handler
                // Since we registered an exception handler, I dont think this will ever be called,
                // simply a precaution.
                self::catchException(null, $errstr, $errfile, $errline);
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

    private static function logError($type, $contents) {

        $dir_exists = false;

        try {

            if (!is_dir(join(DIRECTORY_SEPARATOR, array(ROOT_PATH, 'cache', 'logs')))) {
                if (is_writable(ROOT_PATH . DIRECTORY_SEPARATOR . 'cache')) {
                    mkdir(ROOT_PATH . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'logs');
                    $dir_exists = true;
                }
            } else {
                $dir_exists = true;
            }

            if($dir_exists) {
                file_put_contents(join(DIRECTORY_SEPARATOR, array(ROOT_PATH, 'cache', 'logs', $type . '-log.log')), $contents . PHP_EOL, FILE_APPEND);
            }

        } catch (Exception $exception) {
            // Unable to write to file, ignore for now
        }
    }

    // Log a custom error
    // Not used internally. Only for modules
    public static function logCustomError($contents) {
        self::logError('other', $contents);
    }
}
