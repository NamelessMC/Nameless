<?php
/**
 * Base template engine class to be extended.
 *
 * @author Samerton
 * @license MIT
 * @version 2.2.0
 */
abstract class TemplateEngine
{
    private array $_variables;

    public function __construct()
    {
        $this->_variables = [];
    }

    /**
     * Register a template variable.
     *
     * TODO: mixed type for $value
     *
     * @param  string $key
     * @param  mixed  $value
     * @return void
     */
    public function addVariable(string $key, $value): void
    {
        $this->_variables[$key] = $value;
    }

    /**
     * Get a template variable.
     *
     * @param  string $key
     * @return mixed
     */
    public function getVariable(string $key)
    {
        return $this->_variables[$key];
    }

    /**
     * Register several template variables.
     *
     * @param  array $variables
     * @return void
     */
    public function addVariables(array $variables): void
    {
        $this->_variables = array_merge($this->_variables, $variables);
    }

    /**
     * Get all template variables.
     *
     * @return array
     */
    public function getVariables(): array
    {
        return $this->_variables;
    }

    /**
     * Render a template file using the set template engine.
     *
     * @param string $templateFile Path to template file to render.
     */
    abstract public function render(string $templateFile): void;

    /**
     * Fetch a generated template file using the set template engine.
     *
     * @param  string $templateFile Path to template file to render.
     * @return string HTML to render
     */
    abstract public function fetch(string $templateFile): string;

    /**
     * Clear template cache directory.
     */
    abstract public function clearCache(): void;
}
