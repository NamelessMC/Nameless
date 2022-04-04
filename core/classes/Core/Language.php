<?php
/**
 * Provides utilities for retrieving/handling language strings.
 *
 * @package NamelessMC\Core
 * @author Samerton
 * @version 2.0.0-pr13
 * @license MIT
 */

use samerton\i18next\i18next;

class Language {

    /**
     * @var array Metadata about different languages available
     */
    public const LANGUAGES = [
        'zh_TW' => [
            'name' => 'Chinese',
            'htmlCode' => 'zh-TW',
        ],
        'zh_CN' => [
            'name' => 'Chinese (Simplified)',
            'htmlCode' => 'zh-CN',
        ],
        'cs_CZ' => [
            'name' => 'Czech',
            'htmlCode' => 'cs',
        ],
        'da_DK' => [
            'name' => 'Danish',
            'htmlCode' => 'da',
        ],
        'nl_NL' => [
            'name' => 'Dutch',
            'htmlCode' => 'nl',
        ],
        'en_UK' => [
            'name' => 'English UK',
            'htmlCode' => 'en',
        ],
        'en_US' => [
            'name' => 'English US',
            'htmlCode' => 'en',
        ],
        'fr_FR' => [
            'name' => 'French',
            'htmlCode' => 'fr',
        ],
        'de_DE' => [
            'name' => 'German',
            'htmlCode' => 'de',
        ],
        'el_GR' => [
            'name' => 'Greek',
            'htmlCode' => 'el',
        ],
        'it_IT' => [
            'name' => 'Italian',
            'htmlCode' => 'it',
        ],
        'ja_JP' => [
            'name' => 'Japanese',
            'htmlCode' => 'ja',
        ],
        'lt_LT' => [
            'name' => 'Lithuanian',
            'htmlCode' => 'lt',
        ],
        'no_NO' => [
            'name' => 'Norwegian',
            'htmlCode' => 'no',
        ],
        'pl_PL' => [
            'name' => 'Polish',
            'htmlCode' => 'pl',
        ],
        'pt_BR' => [
            'name' => 'Portuguese',
            'htmlCode' => 'pt',
        ],
        'ro_RO' => [
            'name' => 'Romanian',
            'htmlCode' => 'ro',
        ],
        'ru_RU' => [
            'name' => 'Russian',
            'htmlCode' => 'ru',
        ],
        'sk_SK' => [
            'name' => 'Slovak',
            'htmlCode' => 'sk',
        ],
        'es_419' => [
            'name' => 'Spanish',
            'htmlCode' => 'es',
        ],
        'es_ES' => [
            'name' => 'Spanish ES',
            'htmlCode' => 'es',
        ],
        'sv_SE' => [
            'name' => 'Swedish',
            'htmlCode' => 'sv',
        ],
        'th_TH' => [
            'name' => 'Thai',
            'htmlCode' => 'th',
        ],
        'tr_TR' => [
            'name' => 'Turkish',
            'htmlCode' => 'tr',
        ],
    ];

    /**
     * @var string Name of the language translation currently being used.
     */
    private string $_activeLanguage;

    /**
     * @var string Path of the language currently being used.
     */
    private string $_activeLanguageDirectory;

    /**
     * @var i18next Instance of i18next.
     */
    private i18next $_i18n;

    /**
     * Return the current active language code.
     *
     * @return string Active language name.
     */
    public function getActiveLanguage(): string {
        return $this->_activeLanguage;
    }

    /**
     * Return the path to the active language directory.
     *
     * @return string Active language path.
     */
    public function getActiveLanguageDirectory(): string {
        return $this->_activeLanguageDirectory;
    }

    /**
     * Construct Language class
     *
     * @param string $module Path to the custom language files to use, "core" by default for builtin language files.
     * @param string|null $active_language The translation to use.
     * @throws RuntimeException If the language file cannot be found.
     */
    public function __construct(string $module = 'core', string $active_language = null) {
        $this->_activeLanguage = $active_language ?? LANGUAGE ?? 'en_UK';

        // Require file
        if ($module == null || $module === 'core') {
            $path = implode(DIRECTORY_SEPARATOR, [ROOT_PATH, 'custom', 'languages', $this->_activeLanguage . '.json']);
        } else {
            $path = str_replace('/', DIRECTORY_SEPARATOR, $module) . DIRECTORY_SEPARATOR . $this->_activeLanguage . '.json';

            if (!file_exists($path)) {
                $path = str_replace('/', DIRECTORY_SEPARATOR, $module) . DIRECTORY_SEPARATOR . 'en_UK.json';
            }
        }

        if (!file_exists($path)) {
            throw new RuntimeException('Language file ' . $path . ' does not exist');
        }

        $this->_activeLanguageDirectory = $path;

        // HTML language definition
        if (!defined('HTML_LANG')) {
            define('HTML_LANG', self::LANGUAGES[$this->_activeLanguage]['htmlCode']);
        }

        // TODO: new language system - none of our languages are RTL
//        if (!defined('HTML_RTL')) {
//            define('HTML_RTL', self::LANGUAGES[$this->_activeLanguage]['rtl'] ?? false);
//        }

        $this->_i18n = new i18next(
            $this->_activeLanguage,
            $this->_activeLanguageDirectory,
            'en_UK'
        );
    }

    /**
     * Return a term in the currently active language
     *
     * @param string $key Section key.
     * @param ?string $term The term to translate.
     * @param array $variables Any variables to pass through to the translation.
     * @return string Translated phrase.
     */
    public function get(string $key, ?string $term, array $variables = []): string {
        if ($term) {
            $key .= '/' . $term;
        }

        return $this->_i18n->getTranslation($key, $variables);
    }

    /**
     * Get a closure that can be used to get a pluralised term in the currently active language,
     * or null if the active language does not support pluralisation.
     *
     * @return Closure(int, array<string>)|null Closure or null if not available.
     */
    public function getPluralForm(): ?Closure {
        if ($this->_activeLanguage === 'ru_RU') {
            return static function (int $count, array $forms) {
                if ($count % 10 === 1 && $count % 100 !== 11) {
                    return $forms[0];
                }
                if ($count % 10 >= 2 && $count % 10 <= 4 && ($count % 100 < 10 || $count % 100 >= 20)) {
                    return $forms[1];
                }
                return $forms[2];
            };
        }

        return null;
    }

    /**
     * Set a term in specific file.
     * Used for email message editing.
     *
     * TODO: new language system
     *
     * @param string $file Name of file without extension to edit.
     * @param string $term Term which value to change.
     * @param string $value New value to set for term.
     */
    public function set(string $file, string $term, string $value): void {
        $editing_file = $this->_activeLanguageDirectory . DIRECTORY_SEPARATOR . $file . '.php';
        if (is_file($editing_file) && is_writable($editing_file)) {
            file_put_contents($editing_file, html_entity_decode(
                str_replace(
                    htmlspecialchars("'" . $term . "'" . ' => ' . "'" . $this->get($file, $term) . "'"),
                    htmlspecialchars("'" . $term . "'" . ' => ' . "'" . $value . "'"),
                    htmlspecialchars(file_get_contents($editing_file))
                )
            ));
        }
    }
}
