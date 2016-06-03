<?php 
/*
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 */
 
/*
 *  Create page header (which css to load)
 */

// Page title first
echo '<title>' . str_replace('&amp;', '&', $title) . ' &bull; ' . $sitename . '</title>';
 
/*
 *  Check to see if the theme actually exists..
 */
if(!is_dir('styles/themes/' . $theme_result)){
	// Doesn't exist
	// Display an error
	Session::flash('global', '<div class="alert alert-danger">' . $general_language['theme_not_exist'] . '</div>');
	
	// Load default css instead
	echo '<link href="' . PATH . 'styles/themes/Bootstrap/css/bootstrap.min.css" rel="stylesheet">' . PHP_EOL;
	echo '<link href="' . PATH . 'styles/themes/Bootstrap/css/custom.css" rel="stylesheet">' . PHP_EOL;
	echo '<link href="' . PATH . 'styles/themes/Bootstrap/css/font-awesome.min.css" rel="stylesheet">' . PHP_EOL;
	
} else {
	// Exists
	// Load the css
	echo '<link href="' . PATH . 'styles/themes/' . $theme_result . '/css/bootstrap.min.css" rel="stylesheet">' . PHP_EOL;
	echo '<link href="' . PATH . 'styles/themes/' . $theme_result . '/css/custom.css" rel="stylesheet">' . PHP_EOL;
	echo '<link href="' . PATH . 'styles/themes/' . $theme_result . '/css/font-awesome.min.css" rel="stylesheet">' . PHP_EOL;
}

// Global
echo '<link href="' . PATH . 'core/assets/css/toastr.css" rel="stylesheet">' . PHP_EOL;
echo '<link href="' . PATH . 'core/assets/css/custom_core.css" rel="stylesheet">' . PHP_EOL;
echo '<link rel="icon" href="' . PATH . 'core/assets/favicon.ico">';

// Custom
foreach($custom_css as $item){
	echo $item;
}

// Google Analytics module
if(isset($ga_script)){
	echo $ga_script;
}