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
    private Smarty $_smarty;

    /**
     * @param  string          $template      Template name to load
     * @param  bool            $panelTemplate Whether this is a panel template or not
     * @throws SmartyException
     */
    public function __construct(string $template, bool $panelTemplate = false)
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

        if ($panelTemplate) {
            $smarty->setTemplateDir(ROOT_PATH . '/custom/panel_templates/' . $template);
        } else {
            $smarty->setTemplateDir(ROOT_PATH . '/custom/templates/' . $template);
        }

        if (defined('PHPDEBUGBAR')) {
            DebugBarHelper::getInstance()->addSmartyCollector($smarty);
        }

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
}
