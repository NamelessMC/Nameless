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

class MinecraftBanner {

    public static function getBackgroundCanvas($width, $height, $background) {
        $texture_size = 32;

        $canvas = imagecreatetruecolor($width, $height);
        if ($background == NULL) {
            $background = imagecreatefrompng(ROOT_PATH . '/uploads/banners/texture.png');
        } else if (file_exists(ROOT_PATH . '/uploads/banners/' . $background)) {
            $background = imagecreatefrompng(ROOT_PATH . '/uploads/banners/' . $background);
        } else {
            if (stristr($background, "http://") || stristr($background, "https://") || file_exists($background)) {
                $info = pathinfo($background);
                $ext = $info['extension'];

                switch ($ext) {
                    case "png":
                        $background = imagecreatefrompng($background);
                        break;
                    case "jpg":
                        $background = imagecreatefromjpeg($background);
                        break;
                    case "gif":
                        $background = imagecreatefromgif($background);
                        break;
                    default:
                        $background = imagecreatefrompng(ROOT_PATH . '/uploads/banners/texture.png');
                }
            } else {
                $background = imagecreatefrompng(ROOT_PATH . '/uploads/banners/texture.png');
            }
        }

        if (imagesx($background) == $texture_size) {
            for ($yPos = 0; $yPos <= ($height / $texture_size); $yPos++) {
                for ($xPos = 0; $xPos <= ($width / $texture_size); $xPos++) {
                    $startX = $xPos * $texture_size;
                    $startY = $yPos * $texture_size;
                    imagecopyresampled($canvas, $background, $startX, $startY, 0, 0
                            , $texture_size, $texture_size
                            , $texture_size, $texture_size);
                }
            }
        } else {
            imagecopyresampled($canvas, $background, 0, 0, 0, 0, $width, $height, $width, $height);
        }

        return $canvas;
    }

    public static function getColours() {
        return array(
            '0' => array(0, 0, 0), //Black
            '1' => array(0, 0, 170), //Dark Blue
            '2' => array(0, 170, 0), //Dark Green
            '3' => array(0, 170, 170), //Dark Aqua
            '4' => array(170, 0, 0), //Dark Red
            '5' => array(170, 0, 170), //Dark Purple
            '6' => array(255, 170, 0), //Gold
            '7' => array(170, 170, 170), //Gray
            '8' => array(85, 85, 85), //Dark Gray
            '9' => array(85, 85, 255), //Blue
            'a' => array(85, 255, 85), //Green
            'b' => array(85, 255, 255), //Aqua
            'c' => array(255, 85, 85), //Red
            'd' => array(255, 85, 85), //Light Purple
            'e' => array(255, 255, 85), //Yellow
            'f' => array(255, 255, 255), //White
        );
    }

    public static function getColourChar() {
        return "§";
    }

    public static function getFontFile() {
        return ROOT_PATH . '/core/assets/fonts/minecraft.ttf';
    }

}
