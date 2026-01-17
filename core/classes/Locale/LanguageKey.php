<?php

/**
 * Represents a language key.
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
    private string $modulePath;
    private array $variables;

    public function __construct(string $section, string $term, array $variables = [], ?string $modulePath = 'core')
    {
        $this->section = $section;
        $this->term = $term;
        $this->variables = $variables;
        $this->modulePath = $modulePath;
    }

    public function translate(string $languageCode): string
    {
        $language = new Language($this->modulePath, $languageCode);

        return $language->get($this->section, $this->term, $this->variables);
    }
}
