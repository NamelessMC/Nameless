<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr5
 *
 *  License: MIT
 *
 *  Error handler class
 */
class ErrorHandler {
	public static function catchError($errno, $errstr, $errfile, $errline){
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

	public static function catchFatalError(){
		$error = error_get_last();

		if($error['type'] === E_ERROR){
			$errstr = $error['message'];
			$errfile = $error['file'];
			$errline = $error['line'];

			define('ERRORHANDLER', true);
			require_once(ROOT_PATH . DIRECTORY_SEPARATOR . 'error.php');
			self::logError('fatal', '[' . date('Y-m-d, H:i:s') . '] ' . $errfile . '(' . $errline . '): ' . $errstr);
			die(1);
		}
	}

	private static function logError($type, $contents){
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