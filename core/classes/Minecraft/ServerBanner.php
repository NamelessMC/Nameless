<?php
/**
 * Abstraction over the MinecraftBanner class.
 *
 * @package NamelessMC\Minecraft
 * @see MinecraftBanner
 * @author games647
 * @version 2.0.0-pr8
 * @license MIT
 * @site https://github.com/games647/Minecraft-Banner-Generator
 */
class ServerBanner {
    /**
     *
     * @param string $address the server address
     * @param string $motd message of the day which should be displayed
     * @param int $players not implemented
     * @param int $max_players not implemented
     * @param null $favicon not implemented
     * @param string|null $background Image Path or Standard Value
     * @param int $ping not implemented
     *
     * @return resource the rendered banner
     */
    public static function server(string $address, string $motd = '§cOffline Server', int $players = -1, int $max_players = -1, $favicon = null, string $background = null, int $ping = 150) {
        $canvas = MinecraftBanner::getBackgroundCanvas(650, 80, $background);

        if ($favicon == null) {
            $favicon = imagecreatefrompng(ROOT_PATH . '/core/assets/img/favicon.png');
        }

        //center the image in y-direction and add padding to the left side
        $favicon_posY = (80 - 64) / 2;
        imagecopy($canvas, $favicon, 3, $favicon_posY, 0, 0, 64, 64);

        $startX = 5 + 64 + 5;

        $white = imagecolorallocate($canvas, 255, 255, 255);
        $titleY = $favicon_posY + 3 * 2 + 13;
        imagettftext($canvas, 13, 0, $startX, $titleY, $white, MinecraftBanner::getFontFile(), $address);

        $motd = str_replace(['§k', '§l', '§m', '§o', '§r'], '', $motd);

        $components = explode(MinecraftBanner::getColourChar(), $motd);

        $nextX = $startX;
        $nextY = 50;
        $last_color = [255, 255, 255];
        foreach ($components as $component) {
            if (empty($component)) {
                continue;
            }

            $color_code = $component[0];
            $colors = MinecraftBanner::getColours();

            //try to find the color rgb to the colro code
            if (isset($colors[$color_code])) {
                $color_rgb = $colors[$color_code];
                $last_color = $color_rgb;
            }

            $text = substr($component, 1);

            $color = imagecolorallocate($canvas, $last_color[0], $last_color[1], $last_color[2]);
            $lines = explode("\n", $text);

            imagettftext($canvas, 12, 0, $nextX, $nextY, $color, MinecraftBanner::getFontFile(), $lines[0]);
            $box = imagettfbbox(12, 0, MinecraftBanner::getFontFile(), $text);
            $text_width = abs($box[4] - $box[0]);
            if (count($lines) > 1) {
                $nextX = $startX;
                $nextY += 3 * 2 + 12;

                imagettftext($canvas, 12, 0, $nextX, $nextY, $color, MinecraftBanner::getFontFile(), $lines[1]);
            } else {
                $nextX += $text_width + 3;
            }
        }

        if ($ping <= 0) {
            $image = imagecreatefrompng(ROOT_PATH . '/core/assets/img/ping/-1.png');
        } else if ($ping <= 150) {
            $image = imagecreatefrompng(ROOT_PATH . '/core/assets/img/ping/5.png');
        } else if ($ping <= 300) {
            $image = imagecreatefrompng(ROOT_PATH . '/core/assets/img/ping/4.png');
        } else if ($ping <= 400) {
            $image = imagecreatefrompng(ROOT_PATH . '/core/assets/img/ping/3.png');
        } else if ($ping <= 500) {
            $image = imagecreatefrompng(ROOT_PATH . '/core/assets/img/ping/2.png');
        } else if ($ping <= 600) {
            $image = imagecreatefrompng(ROOT_PATH . '/core/assets/img/ping/1.png');
        }

        $ping_posX = 650 - 36 - 3;
        imagecopy($canvas, $image, $ping_posX, $favicon_posY, 0, 0, 36, 29);

        $text = $players . ' / ' . $max_players;
        $box = imagettfbbox(14, 0, MinecraftBanner::getFontFile(), $text);
        $text_width = abs($box[4] - $box[0]);

        //center it based on the ping image
        $posY = $favicon_posY + (29 / 2) + 14 / 2;
        $posX = $ping_posX - $text_width - 3 / 2;

        imagettftext($canvas, 14, 0, $posX, $posY, $white, MinecraftBanner::getFontFile(), $text);

        return $canvas;
    }
}
