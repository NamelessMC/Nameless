<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr2
 *
 *  License: MIT
 *
 *  Minecraft server banner
 */

define('PAGE', 'banner');

// Minecraft integration?
if(defined('MINECRAFT') && MINECRAFT === true){
    if(isset($directories[count($directories) - 1]) && !empty($directories[count($directories) - 1])){
        // Server specified
        $banner = $directories[count($directories) - 1];

        if(substr($banner, -4) == '.png')
            $banner = substr($banner, 0, -4);

        $banner = urldecode($banner);

        $server = $queries->getWhere('mc_servers', array('name', '=', $banner));

        if(!count($server))
            die();

        $server = $server[0];

        require('core/includes/motd_format.php');

        $display_ip = $server->ip;
        if(!is_null($server->port) && $server->port != 25565)
            $display_ip .= ':' . $server->port;

        $full_ip = array('ip' => $server->ip . (is_null($server->port) ? '' : ':' . $server->port), 'pre' => $server->pre, 'name' => $server->name);

        $cache->setCache('banner_cache_' . urlencode($server->name));
        if(!$cache->isCached('image')){
            // Internal or external query?
            $query_type = $queries->getWhere('settings', array('name', '=', 'external_query'));
            if (count($query_type)) {
                if ($query_type[0]->value == '1')
                    $query_type = 'external';
                else
                    $query_type = 'internal';
            } else
                $query_type = 'internal';

            $query = MCQuery::singleQuery($full_ip, $query_type, $language, $queries);

            if($query['status_value'] === 1){
                $motd = MC_parseMotdColors($query['motd']);

                $replace = array(
                    '<span style="color:',
                    '">',
                    '</span>',
                    '<br />'
                );

                $motd = str_replace($replace, '', $motd);
                $motd = preg_split('/(?=\n)/', $motd);
                $motd_formatted = array();

                foreach ($motd as $item) {
                    $motd_explode = explode('`', $item);

                    foreach ($motd_explode as $exploded) {
                        $motd_formatted[] = $exploded;
                    }

                }

                $query['motd_formatted'] = $motd_formatted;
            } else {
                $query['motd_formatted'] = array('#aa0000;Offline');
            }

            // Do we need to query for favicon?
            if(!$cache->isCached('favicon')){
                $favicon = imagecreatefromstring(ExternalMCQuery::getFavicon($full_ip['ip']));

                // Cache the favicon for 1 hour
                imagepng($favicon, 'cache/server_fav_' . urlencode($server->name) . '.png');
                imageAlphaBlending($favicon, true);
                imageSaveAlpha($favicon, true);

                $cache->store('favicon', 'true', 3600);
            } else {
                $favicon = imagecreatefrompng('cache/server_fav_' . urlencode($server->name) . '.png');
            }

            // Font
            $font = 'core/assets/fonts/minecraft.ttf';

            // Make the image!
            header('Content-Type: image/png');

            $src = 'uploads/banners/' . $server->banner_background;
            $im = imagecreatefrompng($src);

            // Minecraft colours
            $white = imagecolorallocate($im, 0xFF, 0xFF, 0xFF);
            $light_grey = imagecolorallocate($im, 0xAA, 0xAA, 0xAA);
            $dark_grey = imagecolorallocate($im, 0x55, 0x55, 0x55);
            $black = imagecolorallocate($im, 0, 0, 0);
            $gold = imagecolorallocate($im, 0xFF, 0xAA, 0x00);
            $red = imagecolorallocate($im, 0xAA, 0x00, 0x00);
            $light_red = imagecolorallocate($im, 0xFF, 0x55, 0x55);
            $yellow = imagecolorallocate($im, 0xFF, 0xFF, 0x55);
            $green = imagecolorallocate($im, 0x00, 0xAA, 0x00);
            $light_green = imagecolorallocate($im, 0x55, 0xFF, 0x55);
            $light_blue = imagecolorallocate($im, 0x55, 0xFF, 0xFF);
            $turquoise = imagecolorallocate($im, 0x00, 0xAA, 0xAA);
            $dark_blue = imagecolorallocate($im, 0x00, 0x00, 0xAA);
            $blue = imagecolorallocate($im, 0x55, 0x55, 0xFF);
            $pink = imagecolorallocate($im, 0xFF, 0x55, 0xFF);
            $purple = imagecolorallocate($im, 0xAA, 0x00, 0xAA);

            imagettftext($im, 12, 0, 90, 30, $white, $font, $server->name);

            if(is_array($query['motd_formatted'])){
                $x = 90;

                foreach($query['motd_formatted'] as $item){
                    // Where does the text need to be situated?
                    $text = substr($item, 8);

                    $dimensions = imagettfbbox(10, 0, $font, $text);
                    $textWidth = abs($dimensions[4] - $dimensions[0]);

                    if(strstr($item, "\n") || isset($change)){
                        if (!isset($change)) {
                            $x = 90;
                        }
                        $change = true;
                        $y = 70;
                    } else {
                        $y = 50;
                    }

                    $chars = substr($item, 1, 6);
                    // Set the colour
                    switch ($chars) {
                        case '000000':
                            imagettftext($im, 10, 0, $x, $y, $black, $font, $text);
                            break;
                        case '0000aa':
                            imagettftext($im, 10, 0, $x, $y, $dark_blue, $font, $text);
                            break;
                        case '0000aa':
                            imagettftext($im, 10, 0, $x, $y, $light_green, $font, $text);
                            break;
                        case '00aa00':
                            imagettftext($im, 10, 0, $x, $y, $green, $font, $text);
                            break;
                        case '00aaaa':
                            imagettftext($im, 10, 0, $x, $y, $turquoise, $font, $text);
                            break;
                        case 'aa0000':
                            imagettftext($im, 10, 0, $x, $y, $red, $font, $text);
                            break;
                        case 'aa00aa':
                            imagettftext($im, 10, 0, $x, $y, $purple, $font, $text);
                            break;
                        case 'ffaa00':
                            imagettftext($im, 10, 0, $x, $y, $gold, $font, $text);
                            break;
                        case 'aaaaaa':
                            imagettftext($im, 10, 0, $x, $y, $light_grey, $font, $text);
                            break;
                        case '555555':
                            imagettftext($im, 10, 0, $x, $y, $dark_grey, $font, $text);
                            break;
                        case '5555ff':
                            imagettftext($im, 10, 0, $x, $y, $blue, $font, $text);
                            break;
                        case '55ff55':
                            imagettftext($im, 10, 0, $x, $y, $light_green, $font, $text);
                            break;
                        case '55ffff':
                            imagettftext($im, 10, 0, $x, $y, $light_blue, $font, $text);
                            break;
                        case 'ff5555':
                            imagettftext($im, 10, 0, $x, $y, $light_red, $font, $text);
                            break;
                        case 'ff55ff':
                            imagettftext($im, 10, 0, $x, $y, $pink, $font, $text);
                            break;
                        case 'ffff55':
                            imagettftext($im, 10, 0, $x, $y, $yellow, $font, $text);
                            break;
                        case 'ffffff':
                            imagettftext($im, 10, 0, $x, $y, $white, $font, $text);
                            break;
                    }
                    $x = $x + $textWidth;
                }

            }

            imagettftext($im, 10, 0, 90, 90, $white, $font, $display_ip);

            if($query['status_value'] === 1){
                // Where does the player count need to be situated?
                $text = $query['player_count'] . "/" . $query['player_count_max'];

                $dimensions = imagettfbbox(11, 0, $font, $text);
                $textWidth = abs($dimensions[4] - $dimensions[0]);
                $x = imagesx($im) - $textWidth;
                $x = $x - 40;

                imagettftext($im, 11, 0, $x, 23, $white, $font, $query['player_count'] . "/" . $query['player_count_max']);

                $online = imagecreatefrompng('core/assets/img/online.png');
                imageAlphaBlending($online, true);
                imageSaveAlpha($online, true);

                imagecopymerge($im, $online, 595, 0, 0, 0, 32, 32, 75);
            } else {
                $offline = imagecreatefrompng('core/assets/img/offline.png');
                imageAlphaBlending($offline, true);
                imageSaveAlpha($offline, true);

                imagecopymerge($im, $offline, 595, 0, 0, 0, 32, 32, 75);
            }

            imagecopy($im, $favicon, 10, 20, 0, 0, 64, 64);

            imagepng($im, 'cache/server_' . urlencode($server->name) . '.png');
            imagepng($im);

            imagedestroy($favicon);
            imagedestroy($im);

            // Cache for 2 minutes
            $cache->store('image', 'true', 120);
        } else {
            header('Content-Type: image/png');
            $im = imagecreatefrompng('cache/server_' . urlencode($server->name) . '.png');
            imagepng($im);
            imagedestroy($im);
        }
    }
}