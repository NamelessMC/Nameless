<?php

/**
 * Smarty template engine.
 *
 * @author Samerton
 * @license MIT
 * @version 2.2.0
 */
class SmartyTemplateEngine extends TemplateEngine
{
    protected Smarty_Security $_securityPolicy;
    private Smarty $_smarty;

    /**
     * @param  string          $dir Path to template directory
     * @throws SmartyException
     */
    public function __construct(string $dir)
    {
        $smarty = new Smarty();

        $securityPolicy = new Smarty_Security($smarty);
        $securityPolicy->php_modifiers = [
            'escape',
            'count',
            'key',
            'round',
            'ucfirst',
            'defined',
            'date',
            'explode',
            'implode',
            'strtolower',
            'strtoupper',
        ];
        $securityPolicy->php_functions = [
            'isset',
            'empty',
            'count',
            'sizeof',
            'in_array',
            'is_array',
            'time',
            'nl2br',
            'is_numeric',
            'file_exists',
            'array_key_exists',
        ];
        $securityPolicy->secure_dir = [ROOT_PATH . '/custom/templates', ROOT_PATH . '/custom/panel_templates'];
        $smarty->enableSecurity($securityPolicy);

        $smarty->setCompileDir(ROOT_PATH . '/cache/templates_c');
        $smarty->setTemplateDir($dir);

        if (defined('PHPDEBUGBAR')) {
            DebugBarHelper::getInstance()->addSmartyCollector($smarty);
        }

        $this->_securityPolicy = $securityPolicy;
        $this->_smarty = $smarty;

        parent::__construct();
    }

    public function render(string $templateFile): void
    {
        echo $this->fetch($templateFile);
    }

    public function fetch(string $templateFile): string
    {
        $templateFile = str_replace('.tpl', '', $templateFile);

        $this->_smarty->assign($this->getVariables());

        return $this->_smarty->fetch("$templateFile.tpl");
    }

    public function clearCache(): void
    {
        $this->_smarty->clearAllCache();
    }

    /**
     * Add an extra directory to the Smarty security policy.
     *
     * @param  string $dir Directory to add to policy
     * @return void
     */
    public function addSecurityPolicyDirectory(string $dir): void
    {
        $this->_securityPolicy->secure_dir = [...$this->_securityPolicy->secure_dir, $dir];
        $this->_smarty->enableSecurity($this->_securityPolicy);
    }

    /**
     * Add extra PHP modifiers to the Smarty security policy.
     *
     * @param  array $modifiers Modifiers to add to policy
     * @return void
     */
    public function addSecurityPolicyModifiers(array $modifiers): void
    {
        $this->_securityPolicy->php_modifiers = [...$this->_securityPolicy->php_modifiers, ...$modifiers];
        $this->_smarty->enableSecurity($this->_securityPolicy);
    }

    /**
     * Add extra PHP functions to the Smarty security policy.
     *
     * @param  array $functionNames Function names to add to policy
     * @return void
     */
    public function addSecurityPolicyFunctions(array $functionNames): void
    {
        $this->_securityPolicy->php_functions = [...$this->_securityPolicy->php_functions, ...$functionNames];
        $this->_smarty->enableSecurity($this->_securityPolicy);
    }

    /**
     * Append a value to a Smarty variable
     * This is unique to the Smarty template engine, and there is no equivalent within the Twig template engine.
     *
     * @param  string $key   Smarty variable to append to
     * @param  string $value Value to append to Smarty variable
     * @return void
     */
    public function append(string $key, string $value): void
    {
        $this->_smarty->append($key, $value);
    }
}
