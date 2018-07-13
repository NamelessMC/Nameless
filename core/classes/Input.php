<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr2
 *
 *  License: MIT
 *
 *  Input class
 */
class Input {
	
	// Check that input actually exists
	// Params: $type (string) - check for either POST or GET submission (optional, defaults to POST)
	public static function exists($type = 'post'){
		switch($type) {
			case 'post';
				// Check the $_POST variable
				return (!empty($_POST)) ? true : false;
			break;
			
			case 'get';
				// Check the $_GET variable
				return (!empty($_GET)) ? true : false;
			break;
			
			default:
				// Otherwise, return false
				return false;
			break;
		}
	}
	
	// Get input with the specified name
	// Params: $item (string) - name of element containing input
	public static function get($item){
		// Check to see if the element is within the $_POST variable or the $_GET variable
		if(isset($_POST[$item])){
			// It is within the $_POST variable, return the item
			return $_POST[$item];
			
		} else if(isset($_GET[$item])){
			// It is in the $_GET variable, return the item
			return $_GET[$item];
		}
		
		// It is not in either $_GET or $_POST, return an empty string
		return '';
	}
	
	// Displays a new CKEditor field
	// Params:  $name (string) - name of input field ID
    //          $admin (boolean) - whether to add admin options or not - default false
	public static function createEditor($name = null, $admin = false){
		if($name){
			$editor = '
			window.path = "' . ((defined('CONFIG_PATH')) ? CONFIG_PATH . '/' : '/') . '";
			
			CKEDITOR.replace( \'' . $name . '\', {
				tabSpaces: 4,
				extraPlugins: \'codesnippetgeshi\',
				codeSnippetGeshi_url: \'' . ((defined('CONFIG_PATH')) ? CONFIG_PATH . '/' : '/') . 'core/includes/geshi/colorize.php\',
				
				extraAllowedContent: \'blockquote(blockquote)\',
				// Define the toolbar groups as it is a more accessible solution.

				toolbarGroups: [
					{"name":"basicstyles","groups":["basicstyles"]},
					{"name":"paragraph","groups":["list","align"]},
					{"name":"styles","groups":["styles"]},
					{"name":"colors","groups":["colors"]},
					{"name":"links","groups":["links"]},
					{"name":"insert","groups":["insert","emoji"]}';

			if($admin)
			    $editor .= ',{"name":"mode","groups":["mode"]}';

			$editor .= '],
				
				removeButtons: \'Anchor,Styles,Specialchar,About,Flash,Iframe,Smiley,CodeSnippet,Format\'
			} );';

			if($admin)
			    $editor .= 'CKEDITOR.config.allowedContent = true;';

			$editor .= '
			CKEDITOR.config.skin = \'' . (defined('TEMPLATE_EDITOR_STYLE') ? TEMPLATE_EDITOR_STYLE : 'moono-lisa') . '\';
			CKEDITOR.skinName = \'' . (defined('TEMPLATE_EDITOR_STYLE') ? TEMPLATE_EDITOR_STYLE : 'moono-lisa') . '\';
			CKEDITOR.config.language = \'' . (defined('HTML_LANG') ? HTML_LANG : 'en') . '\';
			CKEDITOR.config.fontawesomePath = path + \'core/assets/css/font-awesome.min.css\';
			CKEDITOR.config.disableNativeSpellChecker = false;
			CKEDITOR.config.width = "auto";
			CKEDITOR.config.enterMode = CKEDITOR.ENTER_BR;
            CKEDITOR.on(\'instanceReady\', function(ev) {
                var editor = ev.editor;
                editor.dataProcessor.htmlFilter.addRules({
                    elements : {
                        a : function( element ) {
                            var url = element.attributes.href;

                            var parser = document.createElement(\'a\');
                            parser.href = url;
        
                            var hostname = parser.hostname;
                            if ( hostname !== window.location.host) {
                                element.attributes.rel = \'nofollow noopener\';
                                element.attributes.target = \'_blank\';
                            }
                        }
                    }
                });
            })
			';
			
			return $editor;
		}
		return null;
	}
}