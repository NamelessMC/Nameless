Image Manipilation Functions. 
----- 
This folder is a repository of independant functions useful to 
watermark, shrink, resize images. 

Install
-----
Since these are separate functions, all you have to do is 
include them in your project, and call the functions like this: 

```php 
<?
// to crop an image. 
// get the crop function first. 
require 'src/utils/func.image-crop.php';

// call the function and pass the right arguments. 
$crop = Bulletproof\crop( 
		'images/my-car.jpg',  // full image path
		'jpg', // the mime type of your image
		100, // the original image width
		200, // the original image height
		50, // the new image width
		25 // the new image height. 
); 
// now 'images/my-car.jpg' is cropped to 50x25 pixels.
?>
```

Bulletproof
-----
If you want to use these function with the [bulletproof][bulletproof], here are some examples: 

##### Resizing
```php 
// include bulletproof and the resize function.
require "path/to/bulletproof.php";
require "src/utils/func.image-resize.php";

$image = new Bulletproof\Image($_FILES);

if($image["picture"]){
	$upload = $image->upload();
	
	if($upload){
		$resize = Bulletproof\resize(
			$image->getFullPath(), 
			$image->getMime(),
			$image->getWidth(),
			$image->getHeight(),
			50,
			50
	 );
	}
}
```
The `crop()` method supports resizing by ratio, checkout the file for more. 

#### Croping
You can crop images the same way.
```php 
// include bulletproof and the crop function.
require "src/utils/func.image-crop.php";
// assuming image is uploaded. 
if($upload){
	$crop = Bulletproof\crop(
		$upload->getFullPath(), 
		$upload->getMime(),
		$upload->getWidth(),
		$upload->getHeight(),
		50,
		50
	);
}
```
#### Watermark
```php 
// require the watermark function
require 'src/utils/func.image-watermark.php';

// the image to watermark
$logo = 'my-logo.png'; 
// where to place the watermark
$position = 'center'; 
// get the width and heigh of the logo
list($logoWidth, $logoHeight) = getimagesize($logo);

if($upload){
	$resize = Bulletproof\resize(
		$upload->getFullPath(), 
		$upload->getMime(),
		$upload->getWidth(),
		$upload->getHeight(),
		$logo, 
		$logoHeight, 
		$logoWidth, 
		$position		
	);
}
```

Contribution 
----- 

You are encouraged to add functions for other features (ex: add text, rotate images .. ) 

LICENSE 
----- 
Check the main [bulletproof][bulletproof] page for the license. 


[bulletproof]: http://github.com/samayo/bulletproof
