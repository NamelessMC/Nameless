<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Minecraft server banner
 */

define('PAGE', 'banner');

if(!function_exists('exif_imagetype'))
	die('exif_imagetype is required to use server banners.');

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
            die('Invalid server');

        $server = $server[0];

        require(ROOT_PATH . '/core/includes/motd_format.php');

        $display_ip = $server->ip;
        if(!is_null($server->port) && $server->port != 25565)
            $display_ip .= ':' . $server->port;

        $full_ip = array('ip' => $server->ip . (is_null($server->port) ? ':' . 25565 : ':' . $server->port), 'pre' => $server->pre, 'name' => $server->name);

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

            if($query['status_value'] != 1)
                $query['motd'] = array('§4Offline');

            // Do we need to query for favicon?
            if(!$cache->isCached('favicon')){
                $favicon = imagecreatefromstring(base64_decode(ltrim(ExternalMCQuery::getFavicon($full_ip['ip']), 'data:image/png;base64')));

                imageAlphaBlending($favicon, true);
                imageSaveAlpha($favicon, true);

                // Cache the favicon for 1 hour
                imagepng($favicon, ROOT_PATH . '/cache/server_fav_' . urlencode($server->name) . '.png');

                $cache->store('favicon', 'true', 3600);
            } else {
                $favicon = imagecreatefrompng(ROOT_PATH . '/cache/server_fav_' . urlencode($server->name) . '.png');
            }

            // Font
            $font = ROOT_PATH . '/core/assets/fonts/minecraft.ttf';

            if($query['status_value'] === 1)
                $image = ServerBanner::server($display_ip, $query['motd'], $query['player_count'], $query['player_count_max'], $favicon, $server->banner_background, 5);
            else
                $image = ServerBanner::server($display_ip, $query['motd'], '?', '?', $favicon, $server->banner_background, 5);

            header('Content-type: image/png');

            imagepng($image, ROOT_PATH . '/cache/server_' . urlencode($server->name) . '.png');
            imagepng($image);

            imagedestroy($favicon);
            imagedestroy($image);

            // Cache for 2 minutes
            $cache->store('image', 'true', 120);
        } else {
            header('Content-Type: image/png');
            $im = imagecreatefrompng(ROOT_PATH . '/cache/server_' . urlencode($server->name) . '.png');
            imagepng($im);
            imagedestroy($im);
        }
    }
}
