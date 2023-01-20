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
     * @param ?string $content Any default content to insert
     * @param bool $mentions Whether to enable mention autocompletion/parsing or not.
     * @param bool $admin Enable admin only features
     *
     * @return string Script to render on page
     */
    public static function createTinyEditor(Language $language, string $name, ?string $content = null, bool $mentions = false, bool $admin = false): string {
        if (
            (defined('DARK_MODE') && DARK_MODE) ||
            (Cookie::exists('nmc_panel_theme') && Cookie::get('nmc_panel_theme') === 'dark')
        ) {
            $skin = 'oxide-dark';
        } else {
            $skin = 'oxide';
        }

        $js = '';

        if ($mentions) {
            $js .= "
                (function () {
                    let flags = (function () {
                        'use strict';
                        tinymce.PluginManager.add('mentions', function (editor, url) {
                            editor.ui.registry.addAutocompleter('autocompleter-mentions', {
                                ch: '@',
                                minChars: 2,
                                columns: 1,
                                fetch: function (pattern) {
                                    return new tinymce.util.Promise(function (resolve) {
                                        fetch('" . URL::build('/queries/mention_users', 'nickname') . "=' + pattern)
                                            .then((resp) => resp.json())
                                            .then(function (data) {
                                                const results = [];
    
                                                for (const user of data) {
                                                    results.push({
                                                        value: '@' + user.nickname,
                                                        text: user.nickname,
                                                        icon: '<img style=\"height:20px; width:20px;\" src=\"' + user.avatar_url + '\">'
                                                    });
                                                }

                                                results.sort((a, b) => a.text.toLowerCase().localeCompare(b.text.toLowerCase()))
    
                                                resolve(results);
                                            });
                                    });
                                },
                                onAction: function (autocompleteApi, rng, value) {
                                    editor.selection.setRng(rng);
                                    editor.insertContent(value);
                                    autocompleteApi.hide();
                                },
                            });
                        });
                    }());
                })();
            ";
        }

        $js .= "
            tinymce.init({
              verify_html: " . ($admin ? 'false' : 'true') . ",
              selector: '#$name',
              browser_spellcheck: true,
              contextmenu: false,
              branding: false,
              menubar: 'table',
              convert_urls: false,
              plugins: [
                'autolink', 'codesample', 'directionality', 'emoticons', " . ($mentions ? "'mentions', " : '') . "
                'hr', 'image', 'link', 'lists', 'spoiler', 'code', 'table',
              ],
              external_plugins: {
                'spoiler': '" . (defined('CONFIG_PATH') ? CONFIG_PATH : '') . "/core/assets/plugins/tinymce_spoiler/plugin.min.js',
              },
              toolbar: 'undo redo | bold italic underline strikethrough formatselect fontsizeselect forecolor backcolor ltr rtl emoticons | alignleft aligncenter alignright alignjustify | codesample " . ($admin ? "code" : "") . " hr image link numlist bullist | spoiler-add spoiler-remove',
              spoiler_caption: '{$language->get('general', 'spoiler')}',
              default_link_target: '_blank',
              skin: '$skin'," .
            ($content ?
                '
                setup: (editor) => {
                  editor.on(\'init\', () => {
                    editor.setContent(' . json_encode($content) . ');
                  });
                },
                '
            : '') . "
              images_upload_handler: function (blobInfo, success, failure, progress) {
                  let xhr, formData;

                  xhr = new XMLHttpRequest();
                  xhr.withCredentials = false;
                  xhr.open('POST', '" . URL::build('/queries/tinymce_image_upload') . "');

                  xhr.upload.onprogress = function (e) {
                    progress(e.loaded / e.total * 100);
                  };

                  xhr.onload = function() {
                    let json;

                    if (xhr.status !== 200) {
                      failure('HTTP Error ' + xhr.status + ': ' + xhr.responseText);
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
                " . ($admin ? 'valid_children: "+body[style],+body[link],+*[*]",' : '') . "
                extended_valid_elements: " . ($admin ?
                    '"script[src|async|defer|type|charset],+@[data-options]"'
                : 'undefined') . "
            });
        ";

        return $js;
    }
}
