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

    private $_activeLanguage,
        $_activeLanguageDirectory,
        $_activeLanguageEntries,
        $_module;

    /**
     * Construct Language class
     * 
     * @param string|null $module Path of language files for custom modules.
     * @param string|null $active_language The active language set in cache.
     */
    public function __construct($module = null, $active_language = null) {
        if (!$active_language) {
            // No active language set, default to EnglishUK
            $this->_activeLanguage = 'EnglishUK';
        } else {
            $this->_activeLanguage = $active_language;
        }

        // Require file
        if (!$module || ($module && $module == 'core')) {
            $path = join(DIRECTORY_SEPARATOR, array(ROOT_PATH, 'custom', 'languages', $this->_activeLanguage));
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
    public function get($file, $term, $number = null) {
        // Ensure the file exists + term is set
        if (!is_file($this->_activeLanguageDirectory . DIRECTORY_SEPARATOR . $file . '.php')) {
            if ($this->_activeLanguage != 'EnglishUK') {
                if (is_file(rtrim($this->_activeLanguageDirectory, $this->_activeLanguage) . DIRECTORY_SEPARATOR . 'EnglishUK' . DIRECTORY_SEPARATOR . $file . '.php')) {
                    if (!isset($this->_activeLanguageEntries[$file])) {
                        require(rtrim($this->_activeLanguageDirectory, $this->_activeLanguage) . DIRECTORY_SEPARATOR . 'EnglishUK' . DIRECTORY_SEPARATOR . $file . '.php');
                        $this->_activeLanguageEntries[$file] = $language;
                    }
                } else {
                    die('Error loading language file ' . Output::getClean($file) . '.php in ' . $this->_module);
                }
            } else {
                die('Error loading language file ' . Output::getClean($file) . '.php in ' . $this->_module);
            }
        } else {
            if (!isset($this->_activeLanguageEntries[$file])) {
                require($this->_activeLanguageDirectory . DIRECTORY_SEPARATOR . $file . '.php');
                $this->_activeLanguageEntries[$file] = $language;
            }
        }

        if (isset($this->_activeLanguageEntries[$file][$term])) {
            // It is set, return it
            if (is_array($this->_activeLanguageEntries[$file][$term])) {
                if (function_exists('pluralForm') && $number != null) {
                    return pluralForm($number, $this->_activeLanguageEntries[$file][$term]);
                } else {
                    return 'Plural form not set for ' . Output::getClean($term);
                }
            } else {
                return $this->_activeLanguageEntries[$file][$term];
            }
        } else {
            // Not set, display an error
            return 'Term ' . Output::getClean($term) . ' not set (file: ' . $file . '.php)';
        }
    }

    /**
     * Set a term in specific file.
     * Used for email message editing.
     * 
     * @param string $file Name of file without extension to edit.
     * @param string $term Term which value to change.
     * @param string $value New value to set for term.
     */
    public function set($file, $term, $value) {
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

    /**
     * Return current time language.
     * 
     * @return string Time lang for use in Timeago class.
     */
    public function getTimeLanguage() {
        $this->get('time', 'time');
        return $this->_activeLanguageEntries['time'];
    }

    /**
     * Return the current active language.
     * 
     * @return string Active language name.
     */
    public function getActiveLanguage() {
        return $this->_activeLanguage;
    }

    /**
     * Return the current active language directory.
     * 
     * @return string Path to active language files.
     */
    public function getActiveLanguageDirectory() {
        return $this->_activeLanguageDirectory;
    }
}
