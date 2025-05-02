<?php

/**
 * Base class which templates should extend to add functionality.
 * Uses Twig template engine.
 *
 * @package NamelessMC\Templates
 * @author Samerton
 * @version 2.2.0
 * @license MIT
 */
abstract class TwigTemplateBase extends TemplateBase
{
    public function __construct(string $name, string $version, string $nameless_version, string $author, string $dir)
    {
        parent::__construct($name, $version, $nameless_version, $author);

        $this->_engine = new TwigTemplateEngine($dir);
    }
}
