<?php
class ComposerModuleWrapper extends Module
{
    private string $_packageName;
    private string $_privateName;
    private string $_authorName;
    private string $_authorHomepage;
    private string $_repositoryUrl;

    // Lifecycle callbacks
    private array $_onInstall = [];
    private array $_onEnable = [];
    private array $_onDisable = [];

    // Misc
    private string $_debugInfoProvider;

    public function __construct(
        string $packageName,
        string $privateName,
        string $displayName,
        string $authorName,
        string $authorHomepage,
        string $moduleVersion,
        string $namelessVersion,
        string $repositoryUrl
    ) {
        $this->_packageName = $packageName;
        $this->_privateName = $privateName;
        $this->_authorName = $authorName;
        $this->_authorHomepage = $authorHomepage;
        $this->_repositoryUrl = $repositoryUrl;

        parent::__construct($this, $displayName, $authorName, $moduleVersion, $namelessVersion);
    }

    public function getPackageName(): string
    {
        return $this->_packageName;
    }

    public function getPrivateName(): string
    {
        return $this->_privateName;
    }

    public function getRepositoryUrl(): string
    {
        return $this->_repositoryUrl;
    }

    public function getAuthor(): string
    {
        return "<a href='{$this->_authorHomepage}' target='_blank' rel='nofollow noopener'>{$this->_authorName}</a>";
    }

    public function setOnInstall(array $callbacks): void
    {
        $this->_onInstall = $callbacks;
    }

    public function setOnEnable(array $callbacks): void
    {
        $this->_onEnable = $callbacks;
    }

    public function setOnDisable(array $callbacks): void
    {
        $this->_onDisable = $callbacks;
    }

    public function setDebugInfoProvider(string $provider): void
    {
        $this->_debugInfoProvider = $provider;
    }

    public function onPageLoad(User $user, Pages $pages, Cache $cache, Smarty $smarty, iterable $navs, Widgets $widgets, ?TemplateBase $template)
    {
        // ...
    }

    public function onInstall()
    {
        $this->callLifecycleHooks($this->_onInstall);
    }

    public function onEnable()
    {
        $this->callLifecycleHooks($this->_onEnable);
    }

    public function onDisable()
    {
        $this->callLifecycleHooks($this->_onDisable);
    }

    public function onUninstall()
    {
        // ...
    }

    public function getDebugInfo(): array
    {
        if (!$this->_debugInfoProvider) {
            return [];
        }

        /** @var \NamelessMC\Framework\Debugging\DebugInfoProvider */
        $provider = self::$container->make($this->_debugInfoProvider);

        return $provider->provide();
    }

    private function callLifecycleHooks(array $hooks): void
    {
        foreach ($hooks as $callback) {
            /** @var \NamelessMC\Framework\ModuleLifecycle\Hook $hook */
            $hook = self::$container->make($callback);
            $hook->execute();
        }
    }
}
