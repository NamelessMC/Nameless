<?php
/**
 * Base class which templates should extend to add functionality
 * Uses Smarty template engine
 *
 * @package NamelessMC\Templates
 * @author Samerton
 * @version 2.2.0
 * @license MIT
 */
abstract class SmartyTemplateBase extends TemplateBase {

    /**
     * @param string $name
     * @param string $version
     * @param string $nameless_version
     * @param string $author
     * @param bool $panelTemplate
     */
    public function __construct(string $name, string $version, string $nameless_version, string $author, bool $panelTemplate = false) {
        parent::__construct($name, $version, $nameless_version, $author);

        $this->_engine = new SmartyTemplateEngine($name, $panelTemplate);
    }
}
