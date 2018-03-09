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
		if(!file_exists(ROOT_PATH . '/core/config.php')) {
			fopen(ROOT_PATH . '/core/config.php', 'w');
		}

		require(ROOT_PATH . '/core/config.php');
		
		$loadedConfig = json_decode(file_get_contents(ROOT_PATH . '/core/config.php'), true);

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
		$file = fopen(ROOT_PATH . '/core/config.php', 'wa+');
		fwrite($file, '<?php' . PHP_EOL . '$conf = ' . var_export($config, true) . ';' . PHP_EOL . '$CONFIG[\'installed\'] = true;');
		return fclose($file);
	}
}
