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

    public const LANGUAGES = [
        'en_UK' => [
            'name' => 'English UK',
        ],
        'nl_NL' => [
            'name' => 'Dutch',
            'htmlCode' => 'nl',
        ],
        'ru_RU' => [
            'name' => 'Russian',
            'htmlCode' => 'ru',
        ],
    ];

    /**
     * @var string Name of the language currently being used.
     */
    private string $_activeLanguage;

    /**
     * @var i18next Instance of i18next.
     */
    private i18next $_i18n;

    /**
     * Return the current active language.
     *
     * @return string Active language name.
     */
    public function getActiveLanguage(): string {
        return $this->_activeLanguage;
    }

    /**
     * Construct Language class
     *
     * @param string $module Path to the custom language files to use, "core" by default for builtin language files.
     * @param string|null $active_language The translation to use.
     * @throws Exception If the language file cannot be found.
     */
    public function __construct(string $module = 'core', string $active_language = null) {
        $this->_activeLanguage = $active_language ?? LANGUAGE ?? 'en_UK';

        // Require file
        if (!$module || $module == 'core') {
            $path = implode(DIRECTORY_SEPARATOR, [ROOT_PATH, 'custom', 'languages', $this->_activeLanguage . '.json']);
        } else {
            $path = str_replace('/', DIRECTORY_SEPARATOR, $module) . DIRECTORY_SEPARATOR . $this->_activeLanguage . '.json';

            if (!file_exists($path)) {
                $path = str_replace('/', DIRECTORY_SEPARATOR, $module) . DIRECTORY_SEPARATOR . 'en_UK.json';
            }
        }

        $this->_activeLanguageFile = $path;

        if (!file_exists($path)) {
            throw new RuntimeException('Language file ' . $path . ' does not exist');
        }

        // HTML language definition
        if (!defined('HTML_LANG')) {
            define('HTML_LANG', self::LANGUAGES[$this->_activeLanguage]['htmlCode'] ?? 'en');
        }

        if (!defined('HTML_RTL')) {
            define('HTML_RTL', self::LANGUAGES[$this->_activeLanguage]['rtl'] ?? false);
        }

        $this->_i18n = new i18next($this->_activeLanguage, $this->_activeLanguageFile, 'en_UK');
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

    public function getPluralForm(): ?Closure {
        switch ($this->_activeLanguage) {
            case 'ru_RU':
                return static function ($count, $forms) {
                    return $count % 10 == 1 && $count % 100 != 11 ? $forms[0] : ($count % 10 >= 2 && $count % 10 <= 4 && ($count % 100 < 10 || $count % 100 >= 20) ? $forms[1] : $forms[2]);
                };
            default:
                return null;
        }
    }

    /**
     * Set a term in specific file.
     * Used for email message editing.
     *
     * TODO: rework!
     *
     * @param string $file Name of file without extension to edit.
     * @param string $term Term which value to change.
     * @param string $value New value to set for term.
     */
    public function set(string $file, string $term, string $value): void {
        $editing_file = ($this->_activeLanguageDirectory . DIRECTORY_SEPARATOR . $file . '.php'); // TODO _activeLanguageDirectory does not exist
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
