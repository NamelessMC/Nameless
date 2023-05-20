<?php
/**
 * Handles rendering the exception page as well as logging errors.
 *
 * @package NamelessMC\Misc
 * @author Samerton
 * @author Aberdeener
 * @version 2.0.0-pr9
 * @license MIT
 */
class ErrorHandler {

    /**
     * Defined for easy changing.
     * This constant indicates how many LOC from each frame's PHP file to show before and after the highlighted line
     */
    private const LINE_BUFFER = 20;

    /**
     * Catch an error. If it is a fatal error, pass execution to catchException(), otherwise make a log entry.
     *
     * @param int $error_number PHP universal error number of this error.
     * @param string $error_string Error message.
     * @param string $error_file Path of file which this error was thrown at.
     * @param int $error_line Line of $error_file which error occurred at.
     * @return bool False if error reporting is disabled, true otherwise.
     */
    public static function catchError(int $error_number, string $error_string, string $error_file, int $error_line): bool {
        if (!(error_reporting() & $error_number)) {
            return false;
        }

        $log_entry = $error_file . '(' . $error_line . ') ' . $error_number . ': ' . $error_string;
        switch ($error_number) {
            case E_USER_ERROR:
                // Pass execution to new error handler.
                // Since we registered an exception handler, I dont think this will ever be called,
                // simply a precaution.
                self::catchException(null, $error_string, $error_file, $error_line);
                break;

            case E_USER_WARNING:
                self::logError('warning', $log_entry);
                break;

            case E_USER_NOTICE:
                self::logError('notice', $log_entry);
                break;

            default:
                self::logError('other', $log_entry);
                break;
        }

        return true;
    }

    /**
     * Used to neatly display exceptions/errors and the trace/frames leading up to it.
     * If this is called manually, the error_string, error_file and error_line must be manually provided,
     * and a single trace frame will be generated for it.
     *
     * @param Throwable|null $exception Exception/Error to catch and render trace from. If null, other variables will be used to render trace.
     * @param string|null $error_string Main error message to be shown on top of page. Used when $exception is null.
     * @param string|null $error_file Path to most recent frame's file. Used when $exception is null.
     * @param int|null $error_line Line in $error_file which caused Exception. Used when $exception is null.
     */
    public static function catchException(?Throwable $exception, ?string $error_string = null, ?string $error_file = null, ?int $error_line = null): void {
        // Define variables based on if a Throwable was caught by the compiler, or if this was called manually
        $error_string = is_null($exception) ? $error_string : $exception->getMessage();
        $error_file = is_null($exception) ? $error_file : $exception->getFile();
        $error_line = is_null($exception) ? (int)$error_line : $exception->getLine();

        // Create a log entry for viewing in staffcp
        self::logError('fatal', $error_file . '(' . $error_line . '): ' . $error_string);

        // If this is an API request, print the error in plaintext and dont render the whole error trace page
        if (self::shouldUsePlainText()) {
            die($error_string . ' in ' . $error_file . ' on line ' . $error_line . (!is_null($exception) ? PHP_EOL . $exception->getTraceAsString() : ''));
        }

        if (Debugging::canViewDetailedError()) {
            $frames = [];

            // Most recent frame is not included in getTrace(), so deal with it individually
            $frames[] = self::parseFrame($exception, $error_file, $error_line);

            $skip_frames = 0;

            // Loop all frames in the exception trace & get relevent information
            if ($exception != null) {

                $i = count($exception->getTrace());

                foreach ($exception->getTrace() as $frame) {

                    // Check if previous frame had same file and line number (ie: DB->query(...) reports same file and line twice in a row)
                    if (end($frames)['file'] == $frame['file'] && end($frames)['line'] == $frame['line']) {
                        ++$skip_frames;
                        continue;
                    }

                    // Skip frame if it is a closure
                    // @phpstan-ignore-next-line (it does not know that $frame['function'] is valid)
                    if (isset($frame['function']) && $frame['function'] === '{closure}') {
                        ++$skip_frames;
                        continue;
                    }

                    $frames[] = self::parseFrame($exception, $frame['file'], $frame['line'], $i);
                    $i--;
                }
            }

            $sql_frames = QueryRecorder::getInstance()->getSqlStack();
        }

        if (defined('LANGUAGE')) {
            $language = new Language('core', LANGUAGE);
        } else {
            // NamelessMC not installed yet
            $language = new Language('core', 'en_UK');
        }

        $path = (defined('CONFIG_PATH') ? CONFIG_PATH : '') . '/core/assets/';
        $site_name = defined('SITE_NAME') ? Output::getClean(SITE_NAME) : 'NamelessMC';

        $smarty = new Smarty();

        $smarty->setCompileDir(ROOT_PATH . '/cache/templates_c');

        $smarty->assign([
            'LANG' => defined('HTML_LANG') ? HTML_LANG : 'en',
            'RTL' => defined('HTML_RTL') && HTML_RTL === true ? ' dir="rtl"' : '',
            'LANG_CHARSET' => defined('LANG_CHARSET') ? LANG_CHARSET : 'utf-8',
            'TITLE' => $language->get('errors', 'fatal_error') . ' - ' . $site_name,
            'SITE_NAME' => $site_name,
            'FOMANTIC_JS' => $path . 'vendor/fomantic-ui/dist/semantic.min.js',
            'FOMANTIC_CSS' => $path . 'vendor/fomantic-ui/dist/semantic.min.css',
            'FONT_AWESOME' => $path . 'vendor/@fortawesome/fontawesome-free/css/all.min.css',
            'JQUERY' => $path . 'vendor/jquery/dist/jquery.min.js',
            'PRISM_CSS' => $path . 'plugins/prism/prism_light_atom.css',
            'PRISM_JS' => $path . 'plugins/prism/prism.js',
            'DETAILED_ERROR' => Debugging::canViewDetailedError(),
            'LOGO' => $path . 'img/namelessmc_logo.png',
            'FATAL_ERROR_TITLE' => $language->get('errors', 'fatal_error_title'),
            'FATAL_ERROR_MESSAGE_ADMIN' => $language->get('errors', 'fatal_error_message_admin'),
            'FATAL_ERROR_MESSAGE_USER' => $language->get('errors', 'fatal_error_message_user'),
            'ERROR_TYPE' => is_null($exception) ? $language->get('general', 'error') : (new ReflectionClass($exception))->getName(),
            'ERROR_STRING' => Output::getClean($error_string),
            'ERROR_FILE' => $error_file,
            'CANCEL' => $language->get('general', 'cancel'),
            'CAN_GENERATE_DEBUG' => Debugging::canGenerateDebugLink(),
            'DEBUG_LINK' => $language->get('admin', 'debug_link'),
            'DEBUG_LINK_INFO' => $language->get('admin', 'debug_link_info'),
            'DEBUG_LINK_URL' => URL::build('/queries/debug_link'),
            'ERROR_SQL_STACK' => $sql_frames ?? [],
            'CURRENT_URL' => HttpUtils::getProtocol() . '://' . HttpUtils::getHeader('Host') . $_SERVER['REQUEST_URI'],
            'FRAMES' => $frames ?? [],
            'SKIP_FRAMES' => $skip_frames ?? 0,
            'BACK' => $language->get('general', 'back'),
            'HOME' => $language->get('general', 'home'),
            'HOME_URL' => URL::build('/'),
            'GENERATE' => $language->get('general', 'generate'),
            'GENERATE_DEBUG_LINK' => $language->get('general', 'generate_debug_link'),
            'CANNOT_READ_FILE' => $language->get('general', 'cannot_read_file'),
            'FRAME' => $language->get('general', 'frame'),
            'SQL_QUERY' => $language->get('general', 'sql_query'),
            'NAMELESSMC_SUPPORT' => $language->get('general', 'namelessmc_support'),
            'NAMELESSMC_DOCS' => $language->get('general', 'namelessmc_documentation'),
            'DEBUG_TOAST_CLICK' => $language->get('admin', 'debug_link_toast', [
                'linkStart' => '<u><a href="{url}" target="_blank">',
                'linkEnd' => '</a></u>',
            ]),
            'DEBUG_CANNOT_GENERATE' => $language->get('general', 'debug_link_cannot_generate'),
            'DEBUG_COPIED' => $language->get('general', 'debug_link_copied'),
        ]);

        $smarty->display(ROOT_PATH . '/core/includes/error.tpl');
        die();
    }

    /**
     * For API requests, query requests and AJAX requests, return a plain text error message.
     *
     * @return bool Whether the error page should be in plain text rather than a user friendly HTML page.
     */
    private static function shouldUsePlainText(): bool {
        $route = $_REQUEST['route'] ?? '';
        return str_contains($route, '/api/v2/') || str_contains($route, '/queries/') || isset($_SERVER['HTTP_X_REQUESTED_WITH']);
    }

    /**
     * Write error to specific log file.
     *
     * @param string $type Which category/file to log this to. Must be: `warning`, `notice`, `other` or `fatal`.
     * @param string $contents The message to be saved.
     */
    private static function logError(string $type, string $contents): void {
        $dir_exists = false;

        try {
            if (!is_dir(implode(DIRECTORY_SEPARATOR, [ROOT_PATH, 'cache', 'logs']))) {
                if (is_writable(ROOT_PATH . DIRECTORY_SEPARATOR . 'cache')) {
                    mkdir(ROOT_PATH . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'logs');
                    $dir_exists = true;
                }
            } else {
                $dir_exists = true;
            }

            if ($dir_exists) {
                file_put_contents(implode(DIRECTORY_SEPARATOR, [ROOT_PATH, 'cache', 'logs', $type . '-log.log']), '[' . date('Y-m-d, H:i:s') . '] ' . $contents . PHP_EOL, FILE_APPEND);
            }
        } catch (Exception $ignored) {
        }
    }

    /**
     * Returns frame array from specified information.
     * Leaving number as null will use Exception trace count + 1 (for most recent frame)
     *
     * @param Throwable|null $exception Exception object caught and whose trace to count. If null, $number will be used for frame number.
     * @param string $frame_file Path to file which was referenced in this frame.
     * @param int $frame_line Line number of $frame_file which Exception was thrown at.
     * @param int|null $number Higher number = more recent frame. If null, will use $exception trace count + 1.
     * @return array This frame in an array form.
     */
    public static function parseFrame(?Throwable $exception, string $frame_file, int $frame_line, ?int $number = null): array {
        $lines = file($frame_file);

        return [
            'number' => is_null($number) ? (is_null($exception) ? 1 : count($exception->getTrace()) + 1) : $number,
            'file' => $frame_file,
            'line' => $frame_line,
            'start_line' => (is_array($lines) && count($lines) >= self::LINE_BUFFER && ($frame_line - self::LINE_BUFFER > 0)) ? ($frame_line - self::LINE_BUFFER) : 1,
            'highlight_line' => (is_array($lines) && count($lines) >= self::LINE_BUFFER && $frame_line - self::LINE_BUFFER > 0) ? (self::LINE_BUFFER + 1) : $frame_line,
            'code' => self::parseFile($lines, $frame_line)
        ];
    }

    /**
     * Create purified and truncated string from a file for use with error source code preview.
     *
     * @param array|bool $lines Array of lines in this file. If false, will return nothing (means PHP cannot access file).
     * @param int $error_line Line to center output around.
     * @return string Truncated string from this file.
     */
    private static function parseFile($lines, int $error_line): string {
        if ($lines == false || count($lines) < 1) {
            return '';
        }

        $line_num = 1;
        $return = '';

        foreach ($lines as $line) {
            if (($error_line - self::LINE_BUFFER) <= $line_num && $line_num <= ($error_line + self::LINE_BUFFER)) {
                $return .= Output::getClean($line);
            }

            $line_num++;
        }

        return $return;
    }

    /**
     * Called at end of every execution on page load.
     * If an error exists, and the type is fatal, pass execution to catchException().
     */
    public static function catchShutdownError(): void {
        $error = error_get_last();

        if ($error === null) {
            return;
        }

        if ($error['type'] === E_ERROR) {
            self::catchException(null, $error['message'], $error['file'], $error['line']);
        }
    }

    /**
     * Log a custom error, uses `other` type.
     * Not used internally, only for modules to use.
     *
     * @param string $contents Error to write to file.
     */
    public static function logCustomError(string $contents): void {
        self::logError('other', $contents);
    }

    /**
     * Write a message to the 'warning' log.
     *
     * @param string $contents Warning to write to file.
     */
    public static function logWarning(string $contents): void {
        self::logError('warning', $contents);
    }

}
