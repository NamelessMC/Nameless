<?php
/**
 * Fake Smarty class to help with migration to 2.2.0 template system
 * It aims to wrap around TemplateBase to ensure $smarty->assign still works until 2.3.0, when this will be removed.
 *
 * @author Samerton
 * @license MIT
 * @version 2.2.0
 * @deprecated
 */
class FakeSmarty
{
    private TemplateEngine $_engine;

    public function __construct(TemplateEngine $engine)
    {
        $this->_engine = $engine;
    }

    public function assign($key, $value = null)
    {
        if (is_string($key)) {
            $this->_engine->addVariable($key, $value);
        }

        if (is_array($key)) {
            $this->_engine->addVariables($key);
        }
    }
}
