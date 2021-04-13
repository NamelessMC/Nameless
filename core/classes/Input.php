<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Input class
 */
class Input {

    /**
     * Check that specified input type exists.
     * 
     * @param string|null $type Check for either POST or GET submission (optional, defaults to POST)
     * @return bool Whether it exists or not.
     */
    public static function exists($type = 'post') {
        switch ($type) {
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

    /**
     * Get input with specified name.
     * 
     * @param string $item Name of element containing input to get.
     * @return string Value of element in input.
     */
    public static function get($item) {
        if (isset($_POST[$item])) {
            return $_POST[$item];
        } else if (isset($_GET[$item])) {
            return $_GET[$item];
        }

        return '';
    }

    /**
     * Displays a new CKEditor field
     * 
     * @param string $name Name of input field ID
     * @param bool|null $admin Whether to add admin options or not - default false
     * @return string Editor javascript code. 
     */
    public static function createEditor($name = null, $admin = false) {
        if ($name) {
            $editor = '
            window.path = "' . ((defined('CONFIG_PATH')) ? CONFIG_PATH . '/' : '/') . '";

            CKEDITOR.replace( \'' . $name . '\', {
                tabSpaces: 4,

                extraAllowedContent: \'blockquote(blockquote)\',
                // Define the toolbar groups as it is a more accessible solution.

                toolbarGroups: [
                    {"name":"basicstyles","groups":["basicstyles"]},
                    {"name":"paragraph","groups":["list","align"]},
                    {"name":"styles","groups":["styles"]},
                    {"name":"colors","groups":["colors"]},
                    {"name":"links","groups":["links"]},
                    {"name":"insert","groups":["insert","emoji"]}';

            if ($admin) {
                $editor .= ',{"name":"mode","groups":["mode"]}';
            }

            $editor .= '],

                removeButtons: \'Anchor,Styles,SpecialChar,About,Flash' . (!$admin ? ',Iframe,Table' : '') . ',Format\'
            } );';

            if ($admin) {
                $editor .= 'CKEDITOR.config.allowedContent = true;';
            }

            $editor .= '
            CKEDITOR.config.extraPlugins = \'uploadimage\';
            CKEDITOR.config.uploadUrl = window.path + \'uploads/upload_image.php\';
            CKEDITOR.config.filebrowserUploadUrl = window.path + \'uploads/upload_image.php\';
            CKEDITOR.config.skin = \'' . (defined('TEMPLATE_EDITOR_STYLE') ? TEMPLATE_EDITOR_STYLE : 'moono-lisa') . '\';
            CKEDITOR.skinName = \'' . (defined('TEMPLATE_EDITOR_STYLE') ? TEMPLATE_EDITOR_STYLE : 'moono-lisa') . '\';
            CKEDITOR.config.language = \'' . (defined('HTML_LANG') ? HTML_LANG : 'en') . '\';
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

    /**
     * Create a new TinyMCE instance
     * 
     * @param Language $language Instance of language class to use for translation.
     * @param string $name Name of input field ID.
     */
    public static function createTinyEditor(Language $language, $name = null) {
        if ($name) {
            $editor = '
            tinymce.init({
              selector: \'#' . $name . '\',
              browser_spellcheck: true,
                contextmenu: false,
              branding: false,
              menubar: false,
              convert_urls: false,
              plugins: \'autolink,codesample,directionality,emoticons,hr,image,link,lists,spoiler\',
              toolbar: \'undo redo | bold italic underline strikethrough fontsizeselect forecolor backcolor ltr rtl | alignleft aligncenter alignright alignjustify | codesample emoticons hr image link numlist bullist | spoiler-add spoiler-remove\',
              spoiler_caption: \'' . $language->get('general', 'spoiler') . '\',
              default_link_target: \'_blank\',
              skin: "' . (defined('TEMPLATE_TINY_EDITOR_STYLE') ? TEMPLATE_TINY_EDITOR_STYLE : 'oxide') . '"
            });
            ';

            return $editor;
        }
        
        return null;
    }
}
