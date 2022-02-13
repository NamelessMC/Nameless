<?php
/**
 * MinecraftBanner class
 *
 * @package NamelessMC\Minecraft
 * @author games647
 * @version 2.0.0-pr8
 * @license MIT
 * @site https://github.com/games647/Minecraft-Banner-Generator
 */
class MinecraftBanner {

    public static function getBackgroundCanvas(int $width, int $height, $background) {
        $texture_size = 32;

        $canvas = imagecreatetruecolor($width, $height);
        if ($background == null) {
            $background = imagecreatefrompng(ROOT_PATH . '/uploads/banners/texture.png');
        } else {
            if (file_exists(ROOT_PATH . '/uploads/banners/' . $background)) {
                $background = imagecreatefrompng(ROOT_PATH . '/uploads/banners/' . $background);
            } else {
                if (stripos($background, 'http://') !== false || stripos($background, 'https://') !== false || file_exists($background)) {
                    $info = pathinfo($background);
                    $ext = $info['extension'];

                    switch ($ext) {
                        case 'png':
                            $background = imagecreatefrompng($background);
                            break;
                        case 'jpg':
                            $background = imagecreatefromjpeg($background);
                            break;
                        case 'gif':
                            $background = imagecreatefromgif($background);
                            break;
                        default:
                            $background = imagecreatefrompng(ROOT_PATH . '/uploads/banners/texture.png');
                    }
                } else {
                    $background = imagecreatefrompng(ROOT_PATH . '/uploads/banners/texture.png');
                }
            }
        }

        if (imagesx($background) == $texture_size) {
            for ($yPos = 0; $yPos <= ($height / $texture_size); $yPos++) {
                for ($xPos = 0; $xPos <= ($width / $texture_size); $xPos++) {
                    $startX = $xPos * $texture_size;
                    $startY = $yPos * $texture_size;
                    imagecopyresampled($canvas, $background, $startX, $startY, 0, 0, $texture_size, $texture_size, $texture_size, $texture_size);
                }
            }
        } else {
            imagecopyresampled($canvas, $background, 0, 0, 0, 0, $width, $height, $width, $height);
        }

        return $canvas;
    }

    public static function getColours(): array {
        return [
            '0' => [0, 0, 0], //Black
            '1' => [0, 0, 170], //Dark Blue
            '2' => [0, 170, 0], //Dark Green
            '3' => [0, 170, 170], //Dark Aqua
            '4' => [170, 0, 0], //Dark Red
            '5' => [170, 0, 170], //Dark Purple
            '6' => [255, 170, 0], //Gold
            '7' => [170, 170, 170], //Gray
            '8' => [85, 85, 85], //Dark Gray
            '9' => [85, 85, 255], //Blue
            'a' => [85, 255, 85], //Green
            'b' => [85, 255, 255], //Aqua
            'c' => [255, 85, 85], //Red
            'd' => [255, 85, 85], //Light Purple
            'e' => [255, 255, 85], //Yellow
            'f' => [255, 255, 255], //White
        ];
    }

    public static function getColourChar(): string {
        return '§';
    }

    public static function getFontFile(): string {
        return ROOT_PATH . '/core/assets/fonts/minecraft.ttf';
    }

}
