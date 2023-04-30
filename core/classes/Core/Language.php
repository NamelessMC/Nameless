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
        'hu_HU' => [
            'name' => 'Hungarian',
            'htmlCode' => 'hu',
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
        'sq_AL' => [
            'name' => 'Albanian',
            'htmlCode' => 'sq',
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
            'name' => 'Swedish SE',
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
        'uk_UA' => [
            'name' => 'Ukrainian',
            'htmlCode' => 'uk',
        ],
        'ko_KR' => [
            'name' => 'Korean (Korea)',
            'htmlCode' => 'ko',
        ],
        'vi_VN' => [
            'name' => 'Vietnamese',
            'htmlCode' => 'vi',
        ],
        'hr_HR' => [
            'name' => 'Croatian',
            'htmlCode' => 'hr',
        ],
        'id_ID' => [
            'name' => 'Indonesian',
            'htmlCode' => 'id',
        ],
        'fi_FI' => [
            'name' => 'Finnish',
            'htmlCode' => 'fi',
        ],
        'lv_LV' => [
            'name' => 'Latvian (Latvia)',
            'htmlCode' => 'lv',
        ],
    ];

    /**
     * @var string Name of the language translation currently being used.
     */
    private string $_activeLanguage;

    /**
     * @var string Path of the language JSON file currently being used.
     */
    private string $_activeLanguageFile;

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
     * Return the path to the active language file.
     *
     * @return string Active language path.
     */
    public function getActiveLanguageFile(): string {
        return $this->_activeLanguageFile;
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
        if ($module === 'core') {
            $path = implode(DIRECTORY_SEPARATOR, [ROOT_PATH, 'custom', 'languages', '__lng__.json']);
        } else {
            $path = str_replace('/', DIRECTORY_SEPARATOR, $module) . DIRECTORY_SEPARATOR . '__lng__.json';
        }

        $this->_activeLanguageFile = str_replace('__lng__', $this->_activeLanguage, $path);

        // HTML language definition
        if (!defined('HTML_LANG')) {
            define('HTML_LANG', self::LANGUAGES[$this->_activeLanguage]['htmlCode']);
        }

        if (!defined('HTML_RTL')) {
            /** @phpstan-ignore-next-line - none of our languages are RTL (yet) */
            define('HTML_RTL', self::LANGUAGES[$this->_activeLanguage]['rtl'] ?? false);
        }

        $this->_i18n = new i18next(
            $this->_activeLanguage,
            $path,
            'en_UK'
        );
    }

    /**
     * Return a term in the currently active language
     *
     * @param string $section Section name.
     * @param ?string $term The term to translate.
     * @param array $variables Any variables to pass through to the translation.
     * @return string Translated phrase.
     */
    public function get(string $section, ?string $term = null, array $variables = []): string {
        if ($term) {
            $section .= '/' . $term;
        }

        return $this->_i18n->getTranslation($section, $variables);
    }

    /**
     * Get a closure that can be used to get a pluralised term in the currently active language,
     * or null if the active language does not support pluralisation.
     *
     * @return Closure(int, array<string>)|null Closure or null if not available.
     */
    public function getPluralForm(): ?Closure {
        if ($this->_activeLanguage === 'ru_RU' || $this->_activeLanguage === 'uk_UA') {
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
     * Used for email message editing & dropdown name editing.
     *
     * @param string $section Name of file without extension to edit.
     * @param string $term Term which value to change.
     * @param string $value New value to set for term.
     */
    public function set(string $section, string $term, string $value): void {
        $json = json_decode(file_get_contents($this->_activeLanguageFile), true);

        $json[$section . '/' . $term] = $value;

        ksort($json);
        file_put_contents($this->_activeLanguageFile, json_encode($json, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
    }

    /**
     * Attempt to get a language code from browser headers for setting an automatic language for guests.
     *
     * @param string $header <code>HTTP_ACCEPT_LANGUAGE</code> header.
     * @return false|array The browsers preferred language and its name, or false if there is no valid preferred language.
     */
    public static function acceptFromHttp(string $header) {
        // If the Intl extension is enabled, try to use the Locale::acceptFromHttp class,
        // which is more accurate than the below method, but often contains more specific languages than we support.
        if (
            extension_loaded('intl') &&
            class_exists(Locale::class) &&
            method_exists(Locale::class, 'acceptFromHttp')
        ) {
            $locale = Locale::acceptFromHttp($header);
            if (array_key_exists($locale, self::LANGUAGES)) {
                return [$locale, self::LANGUAGES[$locale]['name']];
            }
        }

        // "Accept-Language: en-US,en;q=0.5" -> ["en_US", "en"]
        $header_locales = array_map(
            static fn ($pref) => str_replace('-', '_', explode(';q=', $pref)[0]),
            explode(',', $header),
        );

        foreach ($header_locales as $locale) {
            if (array_key_exists($locale, self::LANGUAGES)) {
                return [$locale, self::LANGUAGES[$locale]['name']];
            }
        }

        return false;
    }
}
