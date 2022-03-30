<?php

/**
 * Input class
 *
 * @package NamelessMC\Core
 * @author Samerton
 * @version 2.0.0-pr8
 * @license MIT
 */
class Input {

    /**
     * Check that specified input type exists.
     *
     * @param string $type Check for either POST or GET submission (optional, defaults to POST)
     * @return bool Whether it exists or not.
     */
    public static function exists(string $type = 'post'): bool {
        switch ($type) {
            case 'post';
                // Check the $_POST variable
                return !empty($_POST);
            case 'get';
                // Check the $_GET variable
                return !empty($_GET);
            default:
                // Otherwise, return false
                return false;
        }
    }

    /**
     * Get input with specified name.
     *
     * @param string $item Name of element containing input to get.
     * @return mixed Value of element in input.
     */
    public static function get(string $item) {
        if (isset($_POST[$item])) {
            return $_POST[$item];
        }

        if (isset($_GET[$item])) {
            return $_GET[$item];
        }

        return '';
    }

    /**
     * Create a new TinyMCE instance
     *
     * @param Language $language Instance of language class to use for translation.
     * @param string $name Name of input field ID.
     */
    public static function createTinyEditor(Language $language, string $name): string {
        $skin = defined('TEMPLATE_TINY_EDITOR_DARKMODE') ? 'oxide-dark' : 'oxide';
        return "        
            tinymce.init({
              selector: '#$name',
              browser_spellcheck: true,
              contextmenu: false,
              branding: false,
              menubar: 'table',
              convert_urls: false,
              plugins: [
                'autolink', 'codesample', 'directionality', 'emoticons',
                'hr', 'image', 'link', 'lists', 'spoiler', 'code', 'table',
              ],
              toolbar: 'undo redo | bold italic underline strikethrough formatselect forecolor backcolor ltr rtl emoticons | alignleft aligncenter alignright alignjustify | codesample code hr image link numlist bullist | spoiler-add spoiler-remove',
              spoiler_caption: '{$language->get('general', 'spoiler')}',
              default_link_target: '_blank',
              skin: '$skin',
              images_upload_handler: function (blobInfo, success, failure, progress) {
                  var xhr, formData;
                
                  xhr = new XMLHttpRequest();
                  xhr.withCredentials = false;
                  xhr.open('POST', '" . URL::build('/queries/tinymce_image_upload') . "');
                
                  xhr.upload.onprogress = function (e) {
                    progress(e.loaded / e.total * 100);
                  };
                
                  xhr.onload = function() {
                    var json;
                
                    if (xhr.status === 403) {
                      failure('HTTP Error: ' + xhr.responseText);
                      return;
                    }
                
                    if (xhr.status < 200 || xhr.status >= 300) {
                      failure('Error: ' + xhr.responseText);
                      return;
                    }
                
                    json = JSON.parse(xhr.responseText);
                
                    if (!json || typeof json.location != 'string') {
                      failure('Invalid JSON: ' + xhr.responseText);
                      return;
                    }
                
                    success(json.location);
                  };
                
                  xhr.onerror = function () {
                    failure('Image upload failed due to a XHR Transport error. Code: ' + xhr.status);
                  };
                
                  formData = new FormData();
                  formData.append('file', blobInfo.blob(), blobInfo.filename());
                  formData.append('token', '" . Token::get() . "');
                
                  xhr.send(formData);
                },
            });
        ";
    }
}
