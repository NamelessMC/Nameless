<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Language class
 */
class Language {

    private string $_activeLanguage;
    private string $_activeLanguageDirectory;
    private array $_activeLanguageEntries;
    private string $_module;

    /**
     * Return the current active language.
     *
     * @return string Active language name.
     */
    public function getActiveLanguage(): string {
        return $this->_activeLanguage;
    }

    /**
     * Return the current active language directory.
     *
     * @return string Path to active language files.
     */
    public function getActiveLanguageDirectory(): string {
        return $this->_activeLanguageDirectory;
    }

    /**
     * Construct Language class
     * 
     * @param string|null $module Path of language files for custom modules.
     * @param string|null $active_language The active language set in cache.
     */
    public function __construct(string $module = null, string $active_language = null) {
        $this->_activeLanguage = $active_language ?? 'EnglishUK';

        // Require file
        if (!$module || $module == 'core') {
            $path = join(DIRECTORY_SEPARATOR, [ROOT_PATH, 'custom', 'languages', $this->_activeLanguage]);
            $this->_module = 'Core';
        } else {
            $path = str_replace('/', DIRECTORY_SEPARATOR, $module) . DIRECTORY_SEPARATOR . $this->_activeLanguage;

            if (!is_dir($path)) {
                $path = str_replace('/', DIRECTORY_SEPARATOR, $module) . DIRECTORY_SEPARATOR . 'EnglishUK';
            }

            $this->_module = Output::getClean($module);
        }

        $this->_activeLanguageDirectory = $path;

        // HTML language definition
        if (is_file($path . DIRECTORY_SEPARATOR . 'version.php')) {
            require($path . DIRECTORY_SEPARATOR . 'version.php');

            if (isset($language_html)) {
                if (!defined('HTML_LANG')) {
                    define('HTML_LANG', $language_html);
                }
            }

            if (isset($language_rtl)) {
                if (!defined('HTML_RTL')) {
                    define('HTML_RTL', $language_rtl);
                }
            }
        }
    }

    /**
     * Return a term in the currently active language
     * 
     * @param string $file Name of file to look in, without file extension.
     * @param string $term The term to translate.
     * @param int|null $number Number of items to pass through to a plural function.
     * @return string Translated phrase.
     */
    public function get(string $file, string $term, int $number = null): string {
        // Check if the file exists for this language,
        // if not, use the fallback EnglishUK file. If it doesnt exist, show an error.
        if (is_file($this->_activeLanguageDirectory . DIRECTORY_SEPARATOR . $file . '.php')) {
            if (!isset($this->_activeLanguageEntries[$file])) {
                require($this->_activeLanguageDirectory . DIRECTORY_SEPARATOR . $file . '.php');
                $this->_activeLanguageEntries[$file] = $language;
            }
        } else if (is_file($this->getFallbackFile($file))) {
            require($this->getFallbackFile($file));
            $this->_activeLanguageEntries[$file] = $language;
        } else {
            die('Error loading fallback language file ' . Output::getClean($file) . '.php in ' . $this->_module . ', does ' . $this->_activeLanguageDirectory . ' exist?');
        }

        // Check if this term exists in the language file
        if (!isset($this->_activeLanguageEntries[$file][$term])) {
            return 'Term ' . Output::getClean($term) . ' not set (file: ' . $file . '.php)';
        }

        // If the term is not an array, it is not plural, so we can just return the term
        if (!is_array($this->_activeLanguageEntries[$file][$term])) {
            return $this->_activeLanguageEntries[$file][$term];
        }

        // If the term is an array, it is plural, so we pass it to the languages pluralForm function
        if (function_exists('pluralForm') && $number != null) {
            return pluralForm($number, $this->_activeLanguageEntries[$file][$term]);
        }

        return 'Plural form not set for ' . Output::getClean($term);
    }

    /**
     * Return the fallback EnglishUK language file path for a given file name.
     *
     * @param string $file Name of file to get fallback for
     * @return string Path of fallback file
     */
    private function getFallbackFile(string $file): string {
        return rtrim($this->_activeLanguageDirectory, $this->_activeLanguage) . DIRECTORY_SEPARATOR . 'EnglishUK' . DIRECTORY_SEPARATOR . $file . '.php';
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
     * Set a term in specific file.
     * Used for email message editing.
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
