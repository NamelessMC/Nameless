<?php

/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr13
 *
 *  License: MIT
 *
 *  Language class
 */

use samerton\i18next\i18next;

class Language {

    private string $_activeLanguage;
    private string $_activeLanguageFile;
    private array $_activeLanguageEntries;
    private string $_module;

    /**
     * Construct Language class
     *
     * @param string|null $module Path of language files for custom modules.
     * @param string|null $active_language The active language set in cache.
     *
     * @throws Exception If LANGUAGES not defined (see custom/languages/languages.php) or if language file does not exist
     */
    public function __construct(string $module = null, string $active_language = null) {
        if (!defined('LANGUAGES')) {
            throw new Exception('Languages not initialised');
        }

        $this->_activeLanguage = $active_language ?? 'en_UK';

        // Require file
        if (!$module || $module == 'core') {
            $path = implode(DIRECTORY_SEPARATOR, [ROOT_PATH, 'custom', 'languages', $this->_activeLanguage . '.json']);
            $this->_module = 'Core';
        } else {
            $path = str_replace('/', DIRECTORY_SEPARATOR, $module) . DIRECTORY_SEPARATOR . $this->_activeLanguage . '.json';

            if (!file_exists($path)) {
                $path = str_replace('/', DIRECTORY_SEPARATOR, $module) . DIRECTORY_SEPARATOR . 'en_UK.json';
            }

            $this->_module = Output::getClean($module);
        }

        $this->_activeLanguageFile = $path;

        if (!file_exists($path)) {
            throw new Exception('Language file ' . $path . ' does not exist');
        }

        // HTML language definition
        if (!defined('HTML_LANG')) {
            define('HTML_LANG', (LANGUAGES[$this->_activeLanguage] && LANGUAGES[$this->_activeLanguage]['htmlCode']) ?? 'en');
        }

        if (!defined('HTML_RTL')) {
            define('HTML_RTL', (LANGUAGES[$this->_activeLanguage] && LANGUAGES[$this->_activeLanguage]['rtl']) ?? false);
        }

        i18next::init($this->_activeLanguage, $this->_activeLanguageFile, 'en_UK');
    }

    /**
     * Return the current active language.
     *
     * @return string Active language name.
     */
    public function getActiveLanguage(): string {
        return $this->_activeLanguage;
    }

    /**
     * Return current time language.
     *
     * @return array Time lang for use in TimeAgo class.
     */
    public function getTimeLanguage(): array {
        $this->get('time', 'time');
        return $this->_activeLanguageEntries['time'];
    }

    /**
     * Return a term in the currently active language
     *
     * @param string $key Section key.
     * @param string $term The term to translate.
     * @param array $variables Any variables to pass through to the translation.
     * @return string Translated phrase.
     */
    public function get(string $key, string $term, array $variables = []): string {
        return i18next::getTranslation($key . $term, $variables);
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
        $editing_file = ($this->_activeLanguageDirectory . DIRECTORY_SEPARATOR . $file . '.php');
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
