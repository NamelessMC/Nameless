<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Minecraft server banner
 */

const PAGE = 'banner';

if (!function_exists('exif_imagetype')) {
    die('exif_imagetype is required to use server banners.');
}

// Minecraft integration?
if (Util::getSetting('mc_integration')) {
    if (isset($directories[count($directories) - 1]) && !empty($directories[count($directories) - 1])) {
        // Server specified
        $banner = $directories[count($directories) - 1];

        if (substr($banner, -4) === '.png') {
            $banner = substr($banner, 0, -4);
        }

        $banner = urldecode($banner);

        $server = DB::getInstance()->get('mc_servers', ['name', $banner])->results();

        if (!count($server)) {
            die('Invalid server');
        }

        $server = $server[0];

        $display_ip = $server->ip;
        if (!is_null($server->port) && $server->port != 25565) {
            $display_ip .= ':' . $server->port;
        }

        $full_ip = [
            'ip' => $server->ip . (is_null($server->port) ? ':' . 25565 : ':' . $server->port),
            'pre' => $server->pre,
            'name' => $server->name
        ];

        $cache->setCache('banner_cache_' . urlencode($server->name));
        if (!$cache->isCached('image')) {
            $query = MCQuery::singleQuery($full_ip, 'external', $server->bedrock, $language); // The favicon is always using the external query anyways

            // Do we need to query for favicon?
            if (!$cache->isCached('favicon')) {
                $favicon = imagecreatefromstring(base64_decode(ltrim(ExternalMCQuery::getFavicon($full_ip['ip'], $server->bedrock), 'data:image/png;base64')));
                if ($favicon) {
                    imageAlphaBlending($favicon, true);
                    imageSaveAlpha($favicon, true);
                } else {
                    $favicon = imagecreatefrompng(ROOT_PATH . '/core/assets/img/favicon.png');
                }

                // Cache the favicon for 1 hour
                imagepng($favicon, ROOT_PATH . '/cache/server_fav_' . urlencode($server->name) . '.png');
                $cache->store('favicon', 'true', 3600);
            } else {
                $favicon = imagecreatefrompng(ROOT_PATH . '/cache/server_fav_' . urlencode($server->name) . '.png');
            }

            // remove ".png" from ending (lib expects file name w/o extension)
            if (substr($server->banner_background, -4) === '.png') {
                $background = substr($server->banner_background, 0, -4);
            }

            if ($query['status_value'] === 1) {
                $image = MinecraftBanner\ServerBanner::server($display_ip, $query['motd'], $query['player_count'], $query['player_count_max'], $favicon, $background, 5);
            } else {
                $image = MinecraftBanner\ServerBanner::server($display_ip, '§4Offline Server', '?', '?', $favicon, $background, -1);
            }

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
