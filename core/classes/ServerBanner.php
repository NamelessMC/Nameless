<?php
/*
https://github.com/games647/Minecraft-Banner-Generator/blob/master/LICENSE

The MIT License (MIT)

Copyright (c) 2016

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
*/

class ServerBanner {
    /**
     *
     * @param string $address the server address
     * @param string $motd message of the day which should be displayed
     * @param int $players not implemented
     * @param int $max_players not implemented
     * @param resource $favicon not implemented
     * @param string $background Image Path or Standard Value
     * @param int $ping not implemented
     * @return resource the rendered banner
     */
    public static function server($address, $motd = "§cOffline Server", $players = -1, $max_players = -1, $favicon = null, $background = null, $ping = 150) {
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

        $motd = str_replace(array('§k', '§l', '§m', '§o', '§r'), '', $motd);

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

            //default to white
            $text = $component;
            if (!empty($color_code)) {
                //try to find the color rgb to the colro code
                if (isset($colors[$color_code])) {
                    $color_rgb = $colors[$color_code];
                    $last_color = $color_rgb;
                }

                $text = substr($component, 1);
            }

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

        if ($ping < 0) {
            $image = imagecreatefrompng(ROOT_PATH . '/core/assets/img/ping/-1.png');
        } else if ($ping > 0 && $ping <= 150) {
            $image = imagecreatefrompng(ROOT_PATH . '/core/assets/img/ping/5.png');
        } else if ($ping <= 300) {
            $image = imagecreatefrompng(ROOT_PATH . '/core/assets/img/ping/4.png');
        } else if ($ping <= 400) {
            $image = imagecreatefrompng(ROOT_PATH . '/core/assets/img/ping/3.png');
        } else if ($ping <= 400) {
            $image = imagecreatefrompng(ROOT_PATH . '/core/assets/img/ping/2.png');
        } else if ($ping > 400) {
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
