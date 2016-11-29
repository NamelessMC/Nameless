<?php
/**
 * Image Watermark Function. 
 *
 * @author     D, Simon <samayo@gmail.com>
 * @link       https://github.com/samayo/bulletproof
 * @copyright  Copyright (c) 2015 Simon Daniel
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */
namespace Bulletproof;

 function watermark($image, $mimeType, $imgWidth, $imgHeight, $watermark, $watermarkHeight, $watermarkWidth, $position = "center"){

    // Calculate the watermark position
    switch ($position) {
        case "center":
            $marginBottom  = round($imgHeight / 2);
            $marginRight   = round($imgWidth / 2) - round($watermarkWidth / 2);
            break;

        case "top-left":
            $marginBottom  = round($imgHeight - $watermarkHeight);
            $marginRight   = round($imgWidth - $watermarkWidth);
            break;

        case "bottom-left":
            $marginBottom  = 5;
            $marginRight   = round($imgWidth - $watermarkWidth);
            break;

        case "top-right":
            $marginBottom  = round($imgHeight - $watermarkHeight);
            $marginRight   = 5;
            break;

        default:
            $marginBottom  = 2;
            $marginRight   = 2;
            break;
    }


    $watermark = imagecreatefrompng($watermark);

    switch ($mimeType) {
        case "jpeg":
        case "jpg":
            $createImage = imagecreatefromjpeg($image);
            break;

        case "png":
            $createImage = imagecreatefrompng($image);
            break;

        case "gif":
            $createImage = imagecreatefromgif($image);
            break;

        default:
            $createImage = imagecreatefromjpeg($image);
            break;
    }

    $sx = imagesx($watermark);
    $sy = imagesy($watermark);
    imagecopy(
        $createImage,
        $watermark,
        imagesx($createImage) - $sx - $marginRight,
        imagesy($createImage) - $sy - $marginBottom,
        0,
        0,
        imagesx($watermark),
        imagesy($watermark)
    );


    switch ($mimeType) {
        case "jpeg":
        case "jpg":
             imagejpeg($createImage, $image);
            break;

        case "png":
             imagepng($createImage, $image);
            break;

        case "gif":
             imagegif($createImage, $image);
            break;

        default:
            throw new \Exception("A watermark can only be applied to: jpeg, jpg, gif, png images ");
            break;
    }
}