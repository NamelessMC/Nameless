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

			$loadedConfig = json_decode(file_get_contents(ROOT_PATH . '/core/config.php'), true);

			if(!is_array($loadedConfig)) {
				$loadedConfig = [];
			}

			$config = $loadedConfig;

			$path = explode('/', $key);

			if(!is_array($path)) {
					$config[$key] = $value;
			} else {
					$loc = &$config;
					foreach($path as $step)
					{
						$loc = &$loc[$step];
					}

					$loc = $value;
			}

			return static::write($config);
	}

	public static function write($config) {
			$insert = json_encode($config, JSON_PRETTY_PRINT);

			$file = fopen('core/config.php', 'w');
							fwrite($file, $insert);
			return fclose($file);
	}
}
