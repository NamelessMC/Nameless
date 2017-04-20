<?php
class Config {
	public static function get($path = null) {
		if($path) {
			if(!isset($GLOBALS['config'])) {
				throw new Exception('Config unavailable.');
			}

			$config = $GLOBALS['config'];

			$path = explode('/', $path);

			foreach($path as $bit){
				if(isset($config[$bit])) {
					$config = $config[$bit];
				}
			}

			return $config;
		}

		return false;
	}

	public static function set($key, $value) {
		if(!file_exists('core/config.php')) {
			fopen('core/config.php', 'w');
		}

		require(ROOT_PATH . '/core/config.php');

		if(!isset($conf) || !is_array($conf)) {
			$conf = [];
		}

		$path = explode('/', $key);

		if(!is_array($path)) {
			$conf[$key] = $value;
		} else {
			$loc = &$conf;
			foreach($path as $step)
			{
				$loc = &$loc[$step];
			}

			$loc = $value;
		}

		return static::write($conf);
	}

	public static function write($config) {
		$file = fopen('core/config.php', 'wa+');
		fwrite($file, '<?php' . PHP_EOL . '$conf = ' . var_export($config, true) . ';');
		return fclose($file);
	}
}
