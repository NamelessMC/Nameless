<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr5
 *
 *  License: MIT
 *
 *  Output class
 */
class Output {
	// Purifier object
	private static $_purifier = null;

	// Returns a clean version of an inputted string
	// Params: $input (string) - contains the string which will be cleaned
	public static function getClean($input){
		return str_replace('&amp;', '&', htmlspecialchars($input, ENT_QUOTES));
	}
	// Returns a decoded version of a clean string
	// Params: $input (string) - contains the clean string which will be decoded
	public static function getDecoded($input){
		return htmlspecialchars_decode($input, ENT_QUOTES);
	}

	// Returns a purified version of an inputted string with HTMLPurifier
	// Params: $input (string) - contains the string which will be purified
	public static function getPurified($input){
		// Require HTMLPurifier
		if(!self::$_purifier){
			require_once(join(DIRECTORY_SEPARATOR, array(ROOT_PATH, 'core', 'includes', 'htmlpurifier', 'HTMLPurifier.standalone.php')));

			$purifierConfig = HTMLPurifier_Config::createDefault();

			// Config settings
			$purifierConfig->set('HTML.Doctype', 'XHTML 1.0 Transitional');
			$purifierConfig->set('URI.DisableExternalResources', false);
			$purifierConfig->set('URI.DisableResources', false);
			$purifierConfig->set('HTML.Allowed', 'u,a,p,b,i,small,blockquote,span[style],span[class],p,strong,em,li,ul,ol,div[align],br,img,figure,figcaption');
			$purifierConfig->set('CSS.AllowedProperties', array('text-align', 'display', 'float', 'color', 'background-color', 'background', 'font-size', 'font-family', 'text-decoration', 'font-weight', 'font-style', 'font-size'));
			$purifierConfig->set('CSS.AllowTricky', true);
			$purifierConfig->set('HTML.AllowedAttributes', 'target, rel, href, id, src, height, width, alt, class, *.style');
			$purifierConfig->set('Attr.AllowedFrameTargets', array('_blank', '_self', '_parent', '_top'));
			$purifierConfig->set('Attr.AllowedRel', array('noopener', 'nofollow'));
			$purifierConfig->set('HTML.SafeIframe', true);
			$purifierConfig->set('URI.SafeIframeRegexp', '%^(https?:)?//(www\.youtube(?:-nocookie)?\.com/embed/|player\.vimeo\.com/video/)%');
			$purifierConfig->set('Core.EscapeInvalidTags', true);
			$purifierConfig->set('AutoFormat.Linkify', true);

			$purifierConfig->set('HTML.DefinitionID', 'namelessmc');
			$purifierConfig->set('HTML.DefinitionRev', 1);
			$purifierConfig->set('Cache.DefinitionImpl', null);
			if($def = $purifierConfig->maybeGetRawHTMLDefinition()){
				$def->addElement('figure', 'Block', 'Optional: (figcaption, Flow) | (Flow, figcaption) | Flow', 'Common');
				$def->addElement('figcaption', 'Inline', 'Flow', 'Common');
			}
			self::$_purifier = new HTMLPurifier($purifierConfig);
		}

		// Purify the string
		$purified = self::$_purifier->purify($input);
		return $purified;
	}

}