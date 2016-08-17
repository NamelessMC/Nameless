<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 1.0.12
 *
 *  License: MIT
 *
 *  Output class
 */ 
class Output {
	
	// Returns a clean version of an inputted string
	// Params: $input (string) - contains the string which will be cleaned
	public static function getClean($input){
		return htmlspecialchars($input);
	}
	
	// Returns a purified version of an inputted string with HTMLPurifier
	// Params: $input (string) - contains the string which will be purified
	public static function getPurified($input){
		// Require HTMLPurifier
		$path = join(DIRECTORY_SEPARATOR, array(ROOT_PATH, 'core', 'includes', 'htmlpurifier', 'HTMLPurifier.standalone.php'));
		require_once($path);
		
		$purifierConfig = HTMLPurifier_Config::createDefault();
		
		// Config settings
		$purifierConfig->set('HTML.Doctype', 'XHTML 1.0 Transitional');
		$purifierConfig->set('URI.DisableExternalResources', false);
		$purifierConfig->set('URI.DisableResources', false);
		$purifierConfig->set('HTML.Allowed', 'u,a,p,b,i,small,blockquote,span[style],span[class],p,strong,em,li,ul,ol,div[align],br,img');
		$purifierConfig->set('CSS.AllowedProperties', array('text-align', 'float', 'color','background-color', 'background', 'font-size', 'font-family', 'text-decoration', 'font-weight', 'font-style', 'font-size'));
		$purifierConfig->set('HTML.AllowedAttributes', 'target, href, src, height, width, alt, class, *.style');
		$purifierConfig->set('Attr.AllowedFrameTargets', array('_blank', '_self', '_parent', '_top'));
		$purifierConfig->set('HTML.SafeIframe', true);
		$purifierConfig->set('URI.SafeIframeRegexp', '%^(https?:)?//(www\.youtube(?:-nocookie)?\.com/embed/|player\.vimeo\.com/video/)%');
		$purifier = new HTMLPurifier($purifierConfig);
		
		// Purify the string
		$purified = $purifier->purify($input);
		return $purified;
	}

}