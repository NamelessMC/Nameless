<?php
/**
 * Image Resizing Function. 
 *
 * @author     Daniel, Simon <samayo@gmail.com>
 * @link       https://github.com/samayo/bulletproof
 * @copyright  Copyright (c) 2015 Simon Daniel
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */
namespace Bulletproof;

function resize($image, $mimeType, $imgWidth, $imgHeight, $newWidth, $newHeight, $ratio = FALSE, $upsize = TRUE){           
    
    // First, calculate the height.
    $height = intval($newWidth / $imgWidth * $imgHeight);

    // If the height is too large, set it to the maximum height and calculate the width.
    if ($height > $newHeight) {

        $height = $newHeight;
        $newWidth = intval($height / $imgHeight * $imgWidth);
    }

    // If we don't allow upsizing check if the new width or height are too big.
    if (!$upsize) {
        // If the given width is larger then the image height, then resize it.
        if ($newWidth > $imgWidth) {
            $newWidth = $imgWidth;
            $height = intval($newWidth / $imgWidth * $imgHeight);
        }

        // If the given height is larger then the image height, then resize it.
        if ($height > $imgHeight) {
            $height = $imgHeight;
            $newWidth = intval($height / $imgHeight * $imgWidth);
        }
    }

    if ($ratio == true)
    {
        $source_aspect_ratio = $imgWidth / $imgHeight;
        $thumbnail_aspect_ratio = $newWidth / $newHeight;
        if ($imgWidth <= $newWidth && $imgHeight <= $newHeight) {
            $newWidth = $imgWidth;
            $newHeight = $imgHeight;
        } elseif ($thumbnail_aspect_ratio > $source_aspect_ratio) {
            $newWidth = (int) ($newHeight * $source_aspect_ratio);
            $newHeight = $newHeight;
        } else {
            $newWidth = $newWidth;
            $newHeight = (int) ($newWidth / $source_aspect_ratio);
        }
    }
            
    $imgString = file_get_contents($image);

    $imageFromString = imagecreatefromstring($imgString);
    $tmp = imagecreatetruecolor($newWidth, $newHeight);
    imagecopyresampled(
        $tmp,
        $imageFromString,
        0,
        0,
        0,
        0,
        $newWidth,
        $newHeight,
        $imgWidth,
        $imgHeight
    );

    switch ($mimeType) {
        case "jpeg":
        case "jpg":
            imagejpeg($tmp, $image, 90);
            break;
        case "png":
            imagepng($tmp, $image, 0);
            break;
        case "gif":
            imagegif($tmp, $image);
            break;
        default:
            throw new \Exception(" Only jpg, jpeg, png and gif files can be resized ");
            break;
    }
 
}


