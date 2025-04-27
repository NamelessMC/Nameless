<?php

/**
 * Represents a valid language key that exists in Nameless.
 *
 * @package NamelessMC\Locale
 * @author Aberdeener
 * @version 2.3.0
 * @license MIT
 */
class LanguageKey
{
    private string $section;
    private string $term;
    private string $module;
    private array $variables;

    public function __construct(string $section, string $term, array $variables = [], ?string $module = 'core')
    {
        $language = new Language($module, 'en_UK');
        $translation = $language->get($section, $term);
        if ($translation === $section . '/' . $term) {
            throw new InvalidArgumentException('Invalid language key: ' . $section . '/' . $term);
        }

        $this->section = $section;
        $this->term = $term;
        $this->variables = $variables;
        $this->module = $module;
    }

    public function translate(string $languageCode): string
    {
        $language = new Language($this->module, $languageCode);

        return $language->get($this->section, $this->term, $this->variables);
    }
}
