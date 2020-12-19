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
    // Converting Cyrillic to Latin letters (https://en.wikipedia.org/wiki/ISO_9)
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

    // Recursively remove a directory
    // Params: $directory (string)	- path to directory to remove (required)
    public static function recursiveRemoveDirectory($directory) {
        if ((strpos($directory, 'custom') !== false)) { // safety precaution, only allow deleting files in "custom" directory
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

    // Return an array containing all timezone lists
    // No params
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

    // Transform any plain-text URLs in a string to an HTML anchor tag with href attribute
    // Regex pattern credit: https://daringfireball.net/2010/07/improved_regex_for_matching_urls
    // "This pattern is free for anyone to use, no strings attached. Consider it public domain."
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

    // Get a Minecraft avatar from a UUID
    public static function getAvatarFromUUID($uuid, $size = 128) {
        if (defined('DEFAULT_AVATAR_SOURCE')) {
            if (defined('DEFAULT_AVATAR_PERSPECTIVE'))
                $perspective = DEFAULT_AVATAR_PERSPECTIVE;
            else
                $perspective = 'face';

            switch (DEFAULT_AVATAR_SOURCE) {
                case 'crafatar':
                    if ($perspective == 'face')
                        return 'https://crafatar.com/avatars/' . Output::getClean($uuid) . '?size=' . $size . '&amp;overlay';
                    else
                        return 'https://crafatar.com/renders/head/' . Output::getClean($uuid) . '?overlay';

                    break;

                case 'nameless':
                    // Only supports face currently
                    if (defined('FRIENDLY_URLS') && FRIENDLY_URLS == true)
                        return URL::build('/avatar/' . Output::getClean($uuid));
                    else
                        return ((defined('CONFIG_PATH')) ? CONFIG_PATH . '/' : '/') . 'core/avatar/face.php?u=' . Output::getClean($uuid);

                    break;

                case 'mc-heads':
                    if ($perspective == 'face')
                        return 'https://mc-heads.net/avatar/' . Output::getClean($uuid) . '/' . $size;
                    else
                        return 'https://mc-heads.net/head/' . Output::getClean($uuid) . '/' . $size;

                    break;

                case 'minotar':
                    if ($perspective == 'face')
                        return 'https://minotar.net/helm/' .  Output::getClean($uuid) . '/' . $size . '.png';
                    else
                        return 'https://minotar.net/cube/' .  Output::getClean($uuid) . '/' . $size . '.png';

                    break;

                case 'visage':
                    if ($perspective == 'face')
                        return 'https://visage.surgeplay.com/face/' . $size . '/' . Output::getClean($uuid);
                    else if ($perspective == 'bust')
                        return 'https://visage.surgeplay.com/bust/' . $size . '/' . Output::getClean($uuid);
                    else
                        return 'https://visage.surgeplay.com/head/' . $size . '/' . Output::getClean($uuid);

                    break;

                case 'cravatar':
                default:
                    if ($perspective == 'face')
                        return 'https://cravatar.eu/helmavatar/' . Output::getClean($uuid) . '/' . $size . '.png';
                    else
                        return 'https://cravatar.eu/helmhead/' . Output::getClean($uuid) . '/' . $size . '.png';
                    break;
            }
        } else {
            // Fall back to cravatar
            return 'https://cravatar.eu/helmavatar/' . Output::getClean($uuid) . '/' . $size . '.png';
        }
    }

    // Get avatar source with UUID as {x} and size as {y}
    public static function getAvatarSource() {
        if (defined('DEFAULT_AVATAR_SOURCE')) {
            if (defined('DEFAULT_AVATAR_PERSPECTIVE'))
                $perspective = DEFAULT_AVATAR_PERSPECTIVE;
            else
                $perspective = 'face';

            switch (DEFAULT_AVATAR_SOURCE) {
                case 'crafatar':
                    if ($perspective == 'face')
                        return 'https://crafatar.com/avatars/{x}?size={y}&amp;overlay';
                    else
                        return 'https://crafatar.com/renders/head/{x}?overlay';
                    break;
                case 'nameless':
                    // Only supports face currently
                    if (defined('FRIENDLY_URLS') && FRIENDLY_URLS == true)
                        return URL::build('/avatar/{x}');
                    else
                        return ((defined('CONFIG_PATH')) ? CONFIG_PATH . '/' : '/') . 'core/avatar/face.php?u={x}';
                    break;
                case 'mc-heads':
                    if ($perspective == 'face')
                        return 'https://mc-heads.net/avatar/{x}/{y}';
                    else
                        return 'https://mc-heads.net/head/{x}/{y}';

                    break;

                case 'minotar':
                    if ($perspective == 'face')
                        return 'https://minotar.net/helm/{x}/{y}.png';
                    else
                        return 'https://minotar.net/cube/{x}/{y}.png';

                    break;

                case 'visage':
                    if ($perspective == 'face')
                        return 'https://visage.surgeplay.com/face/{y}/{x}';
                    else if ($perspective == 'bust')
                        return 'https://visage.surgeplay.com/bust/{y}/{x}';
                    else
                        return 'https://visage.surgeplay.com/head/{y}/{x}';

                    break;
                case 'cravatar':
                default:
                    if ($perspective == 'face')
                        return 'https://cravatar.eu/helmavatar/{x}/{y}.png';
                    else
                        return 'https://cravatar.eu/helmhead/{x}/{y}.png';
                    break;
            }
        } else {
            // Fall back to cravatar
            return 'https://cravatar.eu/helmavatar/{x}/{y}.png';
        }
    }

    /*
     *  Get the server name
     */
    public static function getSelfURL($protocol = true) {
        $hostname = Config::get('core/hostname');
        if (is_array($hostname))
            $hostname = $_SERVER['SERVER_NAME'];

        if (strpos($hostname, 'www') === false && defined('FORCE_WWW') && FORCE_WWW) {
            $www = 'www.';
        } else {
            $www = '';
        }

        if ($protocol) {
            if ($_SERVER['SERVER_PORT'] == 80 || $_SERVER['SERVER_PORT'] == 443) {
                $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http') . "://" . $www . Output::getClean($hostname);
            } else {
                $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http') . "://" . $www . Output::getClean($hostname) . ":" . $_SERVER['SERVER_PORT'];
            }
        } else {
            $url = $www . Output::getClean($hostname);
        }

        if (substr($url, -1) !== '/') $url .= '/';

        return $url;
    }

    /**
     * Is a URL internal or external? Accepts full URL and also just a path
     * @param $url string URL/path to check
     * @return boolean whether URL is external or not
     */
    public static function isExternalURL($url) {
        if ($url[0] == '/' && $url[1] != '/') {
            return false;
        }

        $parsed = parse_url($url);

        return !(str_replace('www.', '', rtrim(Util::getSelfURL(false), '/')) == str_replace('www.', '', $parsed['host']));
    }

    // URL-ify a string
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

    /*
     *  Check for Nameless updates
     *  Returns JSON object with information about any updates
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
        curl_setopt($ch, CURLOPT_URL, 'https://namelessmc.com/nl_core/nl2/stats.php?uid=' . $uid . '&version=' . $current_version . '&php_version=' . urlencode(phpversion()));

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

    /*
     *  Get the latest Nameless news
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

    public static function curlGetContents($full_url, $body = null) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $full_url);
        if ($body != null) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        }
        $contents = curl_exec($ch);

        if ($contents) {
            curl_close($ch);
            return $contents;
        } else {
            Log::getInstance()->log(Log::Action('misc/curl_error'), curl_error($ch));
            curl_close($ch);
            return false;
        }
    }

    /*
     *  Add target and rel attributes to external links only
     *  From https://stackoverflow.com/a/53461987
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

    public static function getSetting(DB $db, $setting, $fallback = null) {
        $value = $db->get('settings', array('name', '=', $setting));
        if ($value->count()) return $value->first()->value;
        else return $fallback;
    }

    public static function loadEndpoints($path, $endpoints) {
        $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS));

        foreach ($rii as $file) {
            if ($file->isDir()) {
                self::loadEndpoints($file, $endpoints);
            } else {
                $endpoint_path = $file->getPathName();
                $endpoint_file_name = $file->getFilename();
                require_once($endpoint_path);
                $endpoint_class_name = str_replace('.php', '', $endpoint_file_name);
                $endpoints->add(new $endpoint_class_name);
            }
        }
    }
}
