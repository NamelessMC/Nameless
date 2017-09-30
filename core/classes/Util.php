<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-dev
 *
 *  License: MIT
 *
 *  Util class
 */
class Util {
	
	// Escape a string
	// Params:	$string (string)	- string to be escaped (required)
    public static function escape($string){
    	return htmlentities($string, ENT_QUOTES, 'UTF-8');
    }
	
	// Recursively remove a directory
	// Params: $directory (string)	- path to directory to remove (required)
	public static function recursiveRemoveDirectory($directory){
		if((strpos($directory, 'custom') !== false)){ // safety precaution, only allow deleting files in "custom" directory
			// alright to proceed
		} else {
			return false;
		}
		
		foreach(glob($directory . '/*') as $file){
			if(is_dir($file)) { 
				self::recursiveRemoveDirectory($file);
			} else {
				unlink($file);
			}
		}
		rmdir($directory);
		return true;
	}

	// Returns start and finish array keys (starting from 0) based on page number
	// Params: $p (int)	- page number (required)
	public static function PaginateArray($p){
		if($p == 1){
			$s = 0;
			$f = 9;
		} else {
			$s = ($p - 1) * 10; // Eg, if page 2, start at 10; if page 3, start at 20
			$f = $s + 9; // Eg, if page 2, finish at 29; if page 3, finish at 29
		}
		return array($s, $f);
	}
	
	// Recursively check to see if an item ($needle) is in an array ($haystack)
	// Params: 	$needle (mixed)		- item to search for in array (required)
	//			$haystack (array)	- array to search through for item (required)
	//			$strict (boolean)	- use PHP equals (false) or identical (true) (defaults to false) (optional)
	public static function in_array_r($needle, $haystack, $strict = false){
		foreach($haystack as $item){
			if(($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
				return true;
			}
		}
		return false;
	}
	
	// Check to see if a given date is valid, returning true/false accordingly
	// Params: 	$date (string) 		- date to check
	//			$format (string) 	- date format to use (optional, defaults to 'm/d/Y')
	public static function validateDate($date, $format = 'm/d/Y'){
		$d = DateTime::createFromFormat($format, $date);
		return $d && $d->format($format) == $date;
	}
	
	// Return an array containing all timezone lists
	// No params
	public static function listTimezones(){
		// Array to contain timezones
		$timezones = array();
		
		// Array to contain offsets
		$offsets = array();
		
		// Get all PHP timezones
		$all_timezones = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
		
		// Get current UTC time to calculate offset
		$current = new DateTime('now', new DateTimeZone('UTC'));
		
		foreach($all_timezones as $timezone){
			// Get timezone offset
			$current->setTimezone(new DateTimeZone($timezone));
			
			// Add offset to offset array
			$offsets[] = $current->getOffset();
			
			// Format timezone offset
			$offset = 'GMT ' . intval($current->getOffset() / 3600) . ':' . str_pad(abs(intval($current->getOffset() % 3600 / 60)), 2, 0);
			
			// Prettify timezone name
			$name = Output::getClean(str_replace(array('/', '_'), array(', ', ' '), $timezone));
			
			// Add to timezones array
			$timezones[$timezone] = array('offset' => $offset, 'name' => $name, 'time' => $current->format('H:i'));
			
		}
		
		array_multisort($offsets, $timezones);
		
		return $timezones;
	}
	
	// Transform any plain-text URLs in a string to an HTML anchor tag with href attribute
	// Regex pattern credit: https://daringfireball.net/2010/07/improved_regex_for_matching_urls
	// "This pattern is free for anyone to use, no strings attached. Consider it public domain."
	public static function urlToAnchorTag($text){
	   $pattern = '#(?i)\b((?:https?:(?:/{1,3}|[a-z0-9%])|[a-z0-9.\-]+[.](?:com|net|org|edu|gov|mil|aero|asia|biz|cat|coop|info|int|jobs|mobi|museum|name|post|pro|tel|travel|xxx|ac|ad|ae|af|ag|ai|al|am|an|ao|aq|ar|as|at|au|aw|ax|az|ba|bb|bd|be|bf|bg|bh|bi|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|cr|cs|cu|cv|cx|cy|cz|dd|de|dj|dk|dm|do|dz|ec|ee|eg|eh|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gg|gh|gi|gl|gm|gn|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|im|in|io|iq|ir|is|it|je|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|me|mg|mh|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|mv|mw|mx|my|mz|na|nc|ne|nf|ng|ni|nl|no|np|nr|nu|nz|om|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|ps|pt|pw|py|qa|re|ro|rs|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|Ja|sk|sl|sm|sn|so|sr|ss|st|su|sv|sx|sy|sz|tc|td|tf|tg|th|tj|tk|tl|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)/)(?:[^\s()<>{}\[\]]+|\([^\s()]*?\([^\s()]+\)[^\s()]*?\)|\([^\s]+?\))+(?:\([^\s()]*?\([^\s()]+\)[^\s()]*?\)|\([^\s]+?\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’])|(?:(?<!@)[a-z0-9]+(?:[.\-][a-z0-9]+)*[.](?:com|net|org|edu|gov|mil|aero|asia|biz|cat|coop|info|int|jobs|mobi|museum|name|post|pro|tel|travel|xxx|ac|ad|ae|af|ag|ai|al|am|an|ao|aq|ar|as|at|au|aw|ax|az|ba|bb|bd|be|bf|bg|bh|bi|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|cr|cs|cu|cv|cx|cy|cz|dd|de|dj|dk|dm|do|dz|ec|ee|eg|eh|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gg|gh|gi|gl|gm|gn|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|im|in|io|iq|ir|is|it|je|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|me|mg|mh|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|mv|mw|mx|my|mz|na|nc|ne|nf|ng|ni|nl|no|np|nr|nu|nz|om|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|ps|pt|pw|py|qa|re|ro|rs|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|Ja|sk|sl|sm|sn|so|sr|ss|st|su|sv|sx|sy|sz|tc|td|tf|tg|th|tj|tk|tl|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)\b/?(?!@)))#';
	   $callback = create_function('$matches', '
		   $url = array_shift($matches);
		   $url_parts = parse_url($url);

		   $text = parse_url($url, PHP_URL_HOST) . parse_url($url, PHP_URL_PATH);
		   $text = preg_replace("/^www./", "", $text);

		   $last = -(strlen(strrchr($text, "/"))) + 1;
		   if($last < 0){
		       $text = substr($text, 0, $last) . "&hellip;";
		   }

		   return sprintf(\'<a rel="nofollow" target="_blank" href="%s">%s</a>\', $url, $text);
	   ');

	   return preg_replace_callback($pattern, $callback, $text);
	}
	
	// Parse text with Geshi
	public static function parseGeshi($content = null){
		if($content) {
            require_once('core/includes/geshi/geshi.php');

            $dom = new DOMDocument;

            $dom->loadHTML($content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

            $codeTags = $dom->getElementsByTagName('code');
            $newCodeTags = array();
            $ids = array();

            $i = $codeTags->length - 1;

            while ($i > -1) {
                $code = $codeTags->item($i);
                if ($code->hasAttributes()) {
                    foreach ($code->attributes as $attribute) {
                        if ($attribute->name == 'class') {
                            $class = $attribute->value;

                            if (substr($class, 0, 9) == 'language-') {
                                // Parse with GeSHi
                                $language = substr($class, 9);

                                $geshi = new GeSHi($code->nodeValue, $language);
                                $string = $geshi->parse_code();

                                $newCodeTags[] = $string;

                                $repl = $dom->createElement('span');

                                $id = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 20);
                                $ids[] = '<span id="' . $id . '"></span>';

                                $repl->setAttribute('id', $id);

                                $code->parentNode->replaceChild($repl, $code);
                            }
                        }
                    }
                }
                $i--;
            }

            $content = $dom->saveHTML();

            return str_replace($ids, $newCodeTags, $content);
		}
		return false;
	}
	
	// Get a Minecraft avatar from a UUID
	public static function getAvatarFromUUID($uuid, $size = 128){
		if(defined('DEFAULT_AVATAR_SOURCE')){
			if(defined('DEFAULT_AVATAR_PERSPECTIVE'))
				$perspective = DEFAULT_AVATAR_PERSPECTIVE;
			else
				$perspective = 'face';

			switch(DEFAULT_AVATAR_SOURCE){
				case 'crafatar':
					if($perspective == 'face')
						return 'https://crafatar.com/avatars/' . $uuid . '?size=' . $size . '&amp;overlay';
					else
						return 'https://crafatar.com/renders/head/' . $uuid . '?overlay';
					break;
				case 'nameless':
					// Only supports face currently
					if(defined('FRIENDLY_URLS') && FRIENDLY_URLS == true)
						return URL::build('/avatar/' . Output::getClean($uuid));
					else
						return ((defined('CONFIG_PATH')) ? CONFIG_PATH . '/' : '/') . 'core/avatar/face.php?u=' . Output::getClean($uuid);
					break;
				case 'cravatar':
				default:
					if($perspective == 'face')
						return 'https://cravatar.eu/helmavatar/' . $uuid . '/' . $size . '.png';
					else
						return 'https://cravatar.eu/helmhead/' . $uuid . '/' . $size . '.png';
					break;
			}
		} else {
			// Fall back to cravatar
			return 'https://cravatar.eu/helmavatar/' . $uuid . '/' . $size . '.png';
		}
	}

	/*
	 *  Get the server name
	 */
    public static function getSelfURL(){
        if($_SERVER['SERVER_ADDR'] !== "127.0.0.1"){
            if($_SERVER['SERVER_PORT'] == 80 || $_SERVER['SERVER_PORT'] == 443){
                $url = $_SERVER['REQUEST_SCHEME']."://".$_SERVER['SERVER_NAME'];
            } else {
                $url = $_SERVER['REQUEST_SCHEME']."://".$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT'];
            }

            if(substr($url, -1) !== '/') $url .= '/';

            return $url;

        } else {
            return false;
        }
    }
}