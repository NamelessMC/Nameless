<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Util class
 */

class Util {

    /**
     * Convert Cyrillic to Latin letters.
     * https://en.wikipedia.org/wiki/ISO_9.
     *
     * @param string $string String to convert.
     * @return string Converted string.
     */
    public static function cyrillicToLatin($string) {
        $cyrillic = [
            'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п',
            'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я',
            'А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П',
            'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я'
        ];
        $latin = [
            'a', 'b', 'v', 'g', 'd', 'e', 'io', 'zh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p',
            'r', 's', 't', 'u', 'f', 'h', 'ts', 'ch', 'sh', 'sht', 'a', 'i', 'y', 'e', 'yu', 'ya',
            'A', 'B', 'V', 'G', 'D', 'E', 'Io', 'Zh', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P',
            'R', 'S', 'T', 'U', 'F', 'H', 'Ts', 'Ch', 'Sh', 'Sht', 'A', 'I', 'Y', 'e', 'Yu', 'Ya'
        ];
        
        return str_replace($cyrillic, $latin, $string);
    }

    /**
     * Recursively remove a directory.
     *
     * @param string $directory Path to directory to remove.
     * @return bool Whether the action succeeded or not.
     */
    public static function recursiveRemoveDirectory($directory) {
        // safety precaution, only allow deleting files in "custom" directory
        if ((strpos($directory, 'custom') !== false)) {
            // alright to proceed
        } else {
            return false;
        }

        foreach (glob($directory . '/*') as $file) {
            if (is_dir($file)) {
                if (!self::recursiveRemoveDirectory($file))
                    return false;
            } else {
                if (!unlink($file))
                    return false;
            }
        }
        rmdir($directory);
        return true;
    }
 
    /**
     * Get an array containing all timezone lists.
     *
     * @return array All timezones.
     */
    public static function listTimezones() {
        // Array to contain timezones
        $timezones = array();

        // Array to contain offsets
        $offsets = array();

        // Get all PHP timezones
        $all_timezones = DateTimeZone::listIdentifiers(DateTimeZone::ALL);

        // Get current UTC time to calculate offset
        $current = new DateTime('now', new DateTimeZone('UTC'));

        foreach ($all_timezones as $timezone) {
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

    /**
     * Transform any plain-text URLs in a string to an HTML anchor tag with href attribute.
     * Regex pattern credit: https://daringfireball.net/2010/07/improved_regex_for_matching_urls.
     *
     * @param string $text String to convert.
     * @return string Converted string.
     */
    public static function urlToAnchorTag($text) {
        $pattern = '#(?i)\b((?:https?:(?:/{1,3}|[a-z0-9%])|[a-z0-9.\-]+[.](?:com|net|org|edu|gov|mil|aero|asia|biz|cat|coop|info|int|jobs|mobi|museum|name|post|pro|tel|travel|xxx|ac|ad|ae|af|ag|ai|al|am|an|ao|aq|ar|as|at|au|aw|ax|az|ba|bb|bd|be|bf|bg|bh|bi|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|cr|cs|cu|cv|cx|cy|cz|dd|de|dj|dk|dm|do|dz|ec|ee|eg|eh|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gg|gh|gi|gl|gm|gn|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|im|in|io|iq|ir|is|it|je|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|me|mg|mh|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|mv|mw|mx|my|mz|na|nc|ne|nf|ng|ni|nl|no|np|nr|nu|nz|om|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|ps|pt|pw|py|qa|re|ro|rs|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|Ja|sk|sl|sm|sn|so|sr|ss|st|su|sv|sx|sy|sz|tc|td|tf|tg|th|tj|tk|tl|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)/)(?:[^\s()<>{}\[\]]+|\([^\s()]*?\([^\s()]+\)[^\s()]*?\)|\([^\s]+?\))+(?:\([^\s()]*?\([^\s()]+\)[^\s()]*?\)|\([^\s]+?\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’])|(?:(?<!@)[a-z0-9]+(?:[.\-][a-z0-9]+)*[.](?:com|net|org|edu|gov|mil|aero|asia|biz|cat|coop|info|int|jobs|mobi|museum|name|post|pro|tel|travel|xxx|ac|ad|ae|af|ag|ai|al|am|an|ao|aq|ar|as|at|au|aw|ax|az|ba|bb|bd|be|bf|bg|bh|bi|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|cr|cs|cu|cv|cx|cy|cz|dd|de|dj|dk|dm|do|dz|ec|ee|eg|eh|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gg|gh|gi|gl|gm|gn|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|im|in|io|iq|ir|is|it|je|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|me|mg|mh|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|mv|mw|mx|my|mz|na|nc|ne|nf|ng|ni|nl|no|np|nr|nu|nz|om|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|ps|pt|pw|py|qa|re|ro|rs|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|Ja|sk|sl|sm|sn|so|sr|ss|st|su|sv|sx|sy|sz|tc|td|tf|tg|th|tj|tk|tl|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)\b/?(?!@)))#';

        return preg_replace_callback($pattern,
            function ($matches) {
                $url = array_shift($matches);

                $text = parse_url($url, PHP_URL_HOST) . parse_url($url, PHP_URL_PATH);
                $text = preg_replace("/^www./", "", $text);

                $last = - (strlen(strrchr($text, "/"))) + 1;
                if ($last < 0) {
                    $text = substr($text, 0, $last) . "&hellip;";
                }

                return sprintf('<a rel="nofollow noopener" target="_blank" href="%s">%s</a>', $url, $text);
            }, 
        $text);
    }

    /**
     * Get a Minecraft avatar from a UUID.
     *
     * @param string $uuid UUID to get avatar for.
     * @param int $size Size of avatar in pixels to get URL for.
     * @return string URL to avatar.
     */
    public static function getAvatarFromUUID($uuid, $size = 128) {
        return AvatarSource::getAvatarFromUUID($uuid, $size);
    }

    /**
     * Get avatar source with UUID as `{x}` and size as `{y}`.
     * Used for avatar preview in online players list.
     *
     * @return string URL to be formatted.
     */
    public static function getAvatarSource() {
        return AvatarSource::getUrlToFormat();
    }

    /**
     * Detect if the current connection is using SSL.
     * @return boolean Whether SSL is in use or not.
     */
    public static function isConnectionSSL() {
        return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https');
    }

    /**
     * Get the server name.
     *
     * @param bool $protocol Whether to show http(s) at front or not.
     * @return string Compiled URL.
     */
    public static function getSelfURL($protocol = true) {
        $hostname = Config::get('core/hostname');

        if (!$hostname) {
            $hostname = $_SERVER['SERVER_NAME'];
        }

        // https and www checks
        if (Util::isConnectionSSL()) {
            $proto = 'https://';
        } else {
            $proto = 'http://';
        }

        if (strpos($hostname, 'www') === false && defined('FORCE_WWW') && FORCE_WWW) {
            $www = 'www.';
        } else {
            $www = '';
        }

        if ($protocol) {
            if ($_SERVER['SERVER_PORT'] == 80 || $_SERVER['SERVER_PORT'] == 443) {
                $url = $proto . $www . Output::getClean($hostname);
            } else {
                $url = $proto . $www . Output::getClean($hostname) . ":" . $_SERVER['SERVER_PORT'];
            }
        } else {
            $url = $www . Output::getClean($hostname);
        }

        if (substr($url, -1) !== '/') {
            $url .= '/';
        }

        return $url;
    }

    /**
     * Is a URL internal or external? Accepts full URL and also just a path.
     * 
     * @param string $url URL/path to check.
     * @return bool Whether URL is external or not.
     */
    public static function isExternalURL($url) {
        if ($url[0] == '/' && $url[1] != '/') {
            return false;
        }

        $parsed = parse_url($url);

        return !(str_replace('www.', '', rtrim(Util::getSelfURL(false), '/')) == str_replace('www.', '', $parsed['host']));
    }
 
    /**
     * URL-ify a string
     *
     * @param string $string String to URLify
     * @return string Url-ified string. (I dont know what this means)
     */
    public static function stringToURL($string = null) {
        if ($string) {
            $string = preg_replace("/[^A-Za-z0-9 ]/", '', $string);
            return Output::getClean(strtolower(urlencode(str_replace(' ', '-', htmlspecialchars_decode($string)))));
        }

        return '';
    }

    /*
     *  The truncate function is taken from CakePHP, license MIT
     *  https://github.com/cakephp/cakephp/blob/master/LICENSE
     */
    /**
     * Truncates text.
     *
     * Cuts a string to the length of $length and replaces the last characters
     * with the ending if the text is longer than length.
     *
     * ### Options:
     *
     * - `ending` Will be used as Ending and appended to the trimmed string
     * - `exact` If false, $text will not be cut mid-word
     * - `html` If true, HTML tags would be handled correctly
     *
     * @param string  $text String to truncate.
     * @param integer $length Length of returned string, including ellipsis.
     * @param array $options An array of html attributes and options.
     * @return string Trimmed string.
     * @access public
     * @link http://book.cakephp.org/view/1469/Text#truncate-1625
     */
    public static function truncate($text, $length = 750, $options = array()) {
        $default = array(
            'ending' => '...', 'exact' => true, 'html' => false
        );
        $options = array_merge($default, $options);
        extract($options);

        if ($html) {
            if (mb_strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
                return $text;
            }
            $totalLength = mb_strlen(strip_tags($ending));
            $openTags = array();
            $truncate = '';

            preg_match_all('/(<\/?([\w+]+)[^>]*>)?([^<>]*)/', $text, $tags, PREG_SET_ORDER);
            foreach ($tags as $tag) {
                if (!preg_match('/img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param/s', $tag[2])) {
                    if (preg_match('/<[\w]+[^>]*>/s', $tag[0])) {
                        array_unshift($openTags, $tag[2]);
                    } else if (preg_match('/<\/([\w]+)[^>]*>/s', $tag[0], $closeTag)) {
                        $pos = array_search($closeTag[1], $openTags);
                        if ($pos !== false) {
                            array_splice($openTags, $pos, 1);
                        }
                    }
                }
                $truncate .= $tag[1];

                $contentLength = mb_strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', ' ', $tag[3]));
                if ($contentLength + $totalLength > $length) {
                    $left = $length - $totalLength;
                    $entitiesLength = 0;
                    if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', $tag[3], $entities, PREG_OFFSET_CAPTURE)) {
                        foreach ($entities[0] as $entity) {
                            if ($entity[1] + 1 - $entitiesLength <= $left) {
                                $left--;
                                $entitiesLength += mb_strlen($entity[0]);
                            } else {
                                break;
                            }
                        }
                    }

                    $truncate .= mb_substr($tag[3], 0, $left + $entitiesLength);
                    break;
                } else {
                    $truncate .= $tag[3];
                    $totalLength += $contentLength;
                }
                if ($totalLength >= $length) {
                    break;
                }
            }
        } else {
            if (mb_strlen($text) <= $length) {
                return $text;
            } else {
                $truncate = mb_substr($text, 0, $length - mb_strlen($ending));
            }
        }
        if (!$exact) {
            $spacepos = mb_strrpos($truncate, ' ');
            if (isset($spacepos)) {
                if ($html) {
                    $bits = mb_substr($truncate, $spacepos);
                    preg_match_all('/<\/([a-z]+)>/', $bits, $droppedTags, PREG_SET_ORDER);
                    if (!empty($droppedTags)) {
                        foreach ($droppedTags as $closingTag) {
                            if (!in_array($closingTag[1], $openTags)) {
                                array_unshift($openTags, $closingTag[1]);
                            }
                        }
                    }
                }
                $truncate = mb_substr($truncate, 0, $spacepos);
            }
        }
        $truncate .= $ending;

        if ($html) {
            foreach ($openTags as $tag) {
                $truncate .= '</' . $tag . '>';
            }
        }

        return $truncate;
    }

    /**
     * Check for Nameless updates.
     *
     * @param string $current_version Current local namelessmc version to compare.
     * @return string JSON object with information about any updates.
     */
    public static function updateCheck($current_version = null) {
        $queries = new Queries();

        // Check for updates
        if (!$current_version) {
            $current_version = $queries->getWhere('settings', array('name', '=', 'nameless_version'));
            $current_version = $current_version[0]->value;
        }

        $uid = $queries->getWhere('settings', array('name', '=', 'unique_id'));
        $uid = $uid[0]->value;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_URL, 'https://namelessmc.com/nl_core/nl2/stats.php?uid=' . $uid . '&version=' . $current_version . '&php_version=' . urlencode(phpversion()) . '&language=' . LANGUAGE . '&docker=' . (getenv('NAMELESSMC_METRICS_DOCKER') == true));

        $update_check = curl_exec($ch);

        if (curl_error($ch)) {
            $error = curl_error($ch);
        } else {
            if ($update_check == 'Failed') {
                $error = 'Unknown error';
            }
        }

        curl_close($ch);

        if (isset($error)) {
            return json_encode(array('error' => $error));
        } else {
            if ($update_check == 'None') {
                return json_encode(array('no_update' => true));
            } else {
                $info = json_decode($update_check);

                if (!isset($info->error) && !isset($info->no_update) && isset($info->new_version)) {
                    if (isset($info->urgent) && $info->urgent == 'true')
                        $to_db = 'urgent';
                    else
                        $to_db = 'true';

                    $update_id = $queries->getWhere('settings', array('name', '=', 'version_update'));
                    $update_id = $update_id[0]->id;
                    $queries->update('settings', $update_id, array(
                        'value' => $to_db
                    ));
                }

                return $update_check;
            }
        }
    }

    /**
     * Get the latest Nameless news.
     *
     * @return string NamelessMC news.
     */
    public static function getLatestNews() {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_URL, 'https://namelessmc.com/news');

        $news = curl_exec($ch);

        if (curl_error($ch)) {
            $error = curl_error($ch);
        }

        curl_close($ch);

        if (isset($error)) {
            return json_encode(array('error' => $error));
        } else {
            return $news;
        }
    }
    
    /**
     * Make a GET request to a URL using cURL.
     * Failures will automatically be logged along with the error.
     * 
     * @param string $full_url URL to send request to.
     * @param string $body Request body to attach to request.
     * @return string|bool Response from remote server, false on failure.
     */
    public static function curlGetContents($full_url, $body = null) {
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $full_url);

        if ($body != null) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        }

        $contents = curl_exec($ch);

        // Make an error log if a curl error occurred
        if ($contents === false) {
            Log::getInstance()->log(Log::Action('misc/curl_error'), curl_error($ch));
            curl_close($ch);

            return false;
        }

        curl_close($ch);

        return $contents;
    }

    /**
     * Add target and rel attributes to external links only.
     * From https://stackoverflow.com/a/53461987
     * 
     * @param string $data Data to replace.
     * @return string Replaced string.
     */
    public static function replaceAnchorsWithText($data) {
        $data = preg_replace_callback('/]*href=["|\']([^"|\']*)["|\'][^>]*>([^<]*)<\/a>/i', function ($m) {
            if (strpos($m[1], self::getSelfURL()) === false)
                return '<a href="' . $m[1] . '" rel="nofollow noopener" target="_blank">' . $m[2] . '</a>';
            else
                return '<a href="' . $m[1] . '" target="_blank">' . $m[2] . '</a>';
        }, $data);

        return $data;
    }
    
    /**
     * Get a setting from the database table `nl2_settings`.
     *
     * @param DB $db Instance of DB class to use.
     * @param string $setting Setting to check.
     * @param mixed $fallback Fallback to return if $setting is not set in DB.
     * @return mixed Setting from DB or $fallback.
     */
    public static function getSetting(DB $db, $setting, $fallback = null) {
        $value = $db->get('settings', array('name', '=', $setting));
        
        if ($value->count()) {
            return $value->first()->value;
        }

        return $fallback;
    }
    
    /**
     * Recursively scan, preload and register EndpointBase classes in a folder.
     *
     * @param string $path Path to scan from.
     * @param Endpoints $endpoints Instance of Endpoints class to register endpoints to.
     */
    public static function loadEndpoints($path, $endpoints) {
        $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS));

        foreach ($rii as $file) {
            if ($file->isDir()) {
                return self::loadEndpoints($file, $endpoints);
            }

            if ($file->getFilename() === '.DS_Store') {
                continue;
            }

            $endpoint_path = $file->getPathName();
            require_once($endpoint_path);

            $endpoint_file_name = $file->getFilename();
            $endpoint_class_name = str_replace('.php', '', $endpoint_file_name);

            $endpoints->add(new $endpoint_class_name);
        }
    }
    
    /**
     * Get in-game rank name from a website group ID, uses Group Sync rules.
     *
     * @param int $website_group_id ID of website group to search for.
     * @return string|null Name of in-game rank or null if rule is not setup.
     */
    public static function getIngameRankName($website_group_id) {
        $data = DB::getInstance()->get('group_sync', array('website_group_id', '=', $website_group_id));
        
        if ($data->count()) {
            return $data->first()->ingame_rank_name;
        }

        return null;
    }
    
    /**
     * Get a website group's name from it's ID.
     *
     * @param int $group_id ID of group to find.
     * @return string|null Name of group, null if doesnt exist.
     */
    public static function getGroupNameFromId($group_id) {
        $data = DB::getInstance()->get('groups', array('id', '=', $group_id));

        if ($data->count()) {
            return $data->first()->name;
        }
        
        return null;
    }
}
