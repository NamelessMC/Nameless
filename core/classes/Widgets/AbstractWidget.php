<?php

abstract class AbstractWidget
{
    protected string $_name;
    protected string $_content;
    protected string $_description;
    protected string $_module;
    protected ?string $_settings = null;
    protected bool $_requires_cookies = false;
    /**
     * Will be removed in 2.3.0.
     * @var ?Smarty
     * @deprecated
     */
    protected ?Smarty $_smarty;
    protected ?TemplateEngine $_engine;
    protected WidgetData $_data;

    private Cache $_cache;

    /**
     * Get the name of this widget.
     *
     * @return string Name of widget.
     */
    public function getName(): string
    {
        return $this->_name;
    }

    /**
     * Get the location (`left` or `right`) that this widget will be displayed on.
     *
     * @return string Location of widget.
     */
    public function getLocation(): string
    {
        return $this->getData()->location;
    }

    /**
     * Get the path to the file for settings of this widget.
     *
     * @return string Widget settings URL.
     */
    public function getSettings(): ?string
    {
        return $this->_settings;
    }

    /**
     * Get the description of this widget.
     *
     * @return string Description of widget.
     */
    public function getDescription(): string
    {
        return $this->_description;
    }

    /**
     * Get the module of this widget.
     *
     * @return string Name of module.
     */
    public function getModule(): string
    {
        return $this->_module;
    }

    /**
     * Get the display order of this widget.
     *
     * @return int Display order of widget.
     */
    public function getOrder(): int
    {
        return $this->getData()->order;
    }

    /**
     * Get Smarty instance in use by this widget. Removed in 2.3.0.
     *
     * @return Smarty Instance in use.
     * @deprecated
     */
    public function getSmarty(): ?Smarty
    {
        return $this->_smarty;
    }

    /**
     * Get template engine in use by this widget.
     *
     * @return TemplateEngine Engine in use.
     */
    public function getTemplateEngine(): ?TemplateEngine
    {
        return $this->_engine;
    }

    /**
     * Render this widget to be displayed on a template page. If this widget requires cookies and cookies are not allowed, a cookie notice is displayed instead.
     * Returns an empty string if this widget is not to be displayed.
     *
     * @throws Exception
     * @throws SmartyException
     *
     * @return string Content/HTML of this widget.
     */
    public function display(): string
    {
        if (defined('COOKIE_CHECK') && !COOKIES_ALLOWED && $this->_requires_cookies) {
            return
                $this->_engine ?
                    $this->_engine->fetch('widgets/cookie_notice.tpl') :
                    $this->_smarty->fetch('widgets/cookie_notice.tpl');
        }

        return $this->_content;
    }

    /**
     * Get pages this widget is enabled on.
     *
     * @return array Pages this widget is enabled on.
     */
    abstract public function getPages(): array;

    /**
     * Clear the cache for this widget, should be called when any settings of it are changed.
     */
    final public function clearCache(): void
    {
        $this->cache()->erase($this->getName());
    }

    /**
     * Get widget data.
     * Will use cache if available, otherwise will query the database and store the result in cache.
     *
     * @return WidgetData Widget data.
     */
    protected function getData(): WidgetData
    {
        if (isset($this->_data)) {
            return $this->_data;
        }

        $cache = $this->cache();

        if ($cache->isCached($this->getName())) {
            return $this->_data = new WidgetData($cache->retrieve($this->getName()));
        }

        $row = DB::getInstance()->get('widgets', ['name', $this->getName()]);
        if ($row->count()) {
            $data = new WidgetData($row->first());
            $cache->store($this->getName(), $data);

            return $this->_data = $data;
        }

        // Widget not found in database, create it
        DB::getInstance()->insert('widgets', $data = [
            'name' => $this->getName(),
            'enabled' => true,
            'location' => 'right',
            'order' => 10,
            'pages' => '["index","forum"]',
        ]);

        $data = new WidgetData((object) $data);
        $cache->store($this->getName(), $data);

        return $this->_data = $data;
    }

    private function cache(): Cache
    {
        $cache = $this->_cache ??= new Cache([
            'name' => 'nameless', 'extension' => '.cache', 'path' => ROOT_PATH . '/cache/',
        ]);

        $cache->setCache(
            $this instanceof ProfileWidgetBase
                ? 'profile_widgets'
                : 'widgets'
        );

        return $cache;
    }
}
