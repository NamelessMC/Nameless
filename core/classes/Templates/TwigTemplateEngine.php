<?php
/**
 * Twig template engine.
 *
 * @author Samerton
 * @license MIT
 * @version 2.2.0
 */

use Twig\Environment;
use Twig\Extension\SandboxExtension;
use Twig\Loader\FilesystemLoader;
use Twig\Profiler\Profile;
use Twig\Sandbox\SecurityPolicy;

class TwigTemplateEngine extends TemplateEngine
{
    private Environment $_twig;

    /**
     * @param string $dir Path to template directory
     */
    public function __construct(string $dir)
    {
        $loader = new FilesystemLoader($dir);
        $twig = new Environment($loader, [
            'cache' => ROOT_PATH . '/cache/twig',
        ]);

        $policy = new SecurityPolicy();
        $sandbox = new SandboxExtension($policy);
        $twig->addExtension($sandbox);

        if (defined('PHPDEBUGBAR')) {
            $profile = new Profile();
            DebugBarHelper::getInstance()->addTwigCollector($twig, $profile);
        }

        $this->_twig = $twig;

        parent::__construct();
    }

    public function render(string $templateFile): void
    {
        echo $this->fetch($templateFile);
    }

    public function fetch(string $templateFile): string
    {
        $templateFile = str_replace('.tpl', '', $templateFile);

        return $this->_twig->render("$templateFile.twig", $this->getVariables());
    }

    public function clearCache(): void
    {
        $dir = ROOT_PATH . '/cache/twig';

        foreach (glob($dir . '/*') as $file) {
            if (is_dir($file)) {
                Util::recursiveRemoveDirectory($file);
            } else {
                unlink($file);
            }
        }
    }
}
