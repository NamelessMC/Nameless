<?php
/**
 * Image Croping Function. 
 *
 * @author     Daniel, Simon <samayo@gmail.com>
 * @link       https://github.com/samayo/bulletproof
 * @copyright  Copyright (c) 2015 Simon Daniel
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */
namespace Bulletproof;

 function crop($image, $mimeType, $imgWidth, $imgHeight, $newWidth, $newHeight){

    switch ($mimeType) {
        case "jpg":
        case "jpeg":
            $imageCreate = imagecreatefromjpeg($image);
            break;

        case "png":
            $imageCreate = imagecreatefrompng($image);
            break;

        case "gif":
            $imageCreate = imagecreatefromgif($image);
            break;

        default:
            throw new \Exception(" Only gif, jpg, jpeg and png files can be cropped ");
            break;
    }

    // The image offsets/coordination to crop the image.
    $widthTrim = ceil(($imgWidth - $newWidth) / 2);
    $heightTrim = ceil(($imgHeight - $newHeight) / 2);

    // Can't crop to a bigger size, ex: 
    // an image with 100X100 can not be cropped to 200X200. Image can only be cropped to smaller size.
    if ($widthTrim < 0 && $heightTrim < 0) {
        return ;
    }

    $temp = imagecreatetruecolor($newWidth, $newHeight);
            imagecopyresampled(
                $temp,
                $imageCreate,
                0,
                0,
                $widthTrim,
                $heightTrim,
                $newWidth,
                $newHeight,
                $newWidth,
                $newHeight
            );


    if (!$temp) {
        throw new \Exception("Failed to crop image. Please pass the right parameters");
    } else {
        imagejpeg($temp, $image);
    }

}

