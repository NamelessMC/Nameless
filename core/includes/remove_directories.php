<?php
/*
 *  Delete files and directories recursively
 */
 
function recursiveRemoveDirectory($directory){
	
	if((strpos($directory, 'styles') !== false)){ // safety precaution
		// alright to proceed
	} else {
		die();
	}
	
	foreach(glob($directory . '/*') as $file)
	{
		if(is_dir($file)) { 
			recursiveRemoveDirectory($file);
		} else {
			unlink($file);
		}
	}
	rmdir($directory);
}