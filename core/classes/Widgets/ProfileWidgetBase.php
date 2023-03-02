<?php

abstract class ProfileWidgetBase implements Widget {

    protected string $_name;
    protected ?string $_location;
    protected string $_content;
    protected string $_description;
    protected string $_module;
    protected ?int $_order;
    protected ?string $_settings = null;
    protected ?Smarty $_smarty = null;

    public function __construct(Smarty $smarty) {
        $this->_smarty = $smarty;
    }

    abstract public function initialise(User $user): void;

    public function getName(): string {
        return $this->_name;
    }

    public function getLocation(): string {
        return $this->_location;
    }

    public function getSettings(): ?string {
        return $this->_settings;
    }

    public function getDescription(): string {
        return $this->_description;
    }

    public function getModule(): string {
        return $this->_module;
    }

    public function getOrder(): ?int {
        return $this->_order;
    }

    public function getSmarty(): ?Smarty {
        return $this->_smarty;
    }

    final public function getPages(): array {
        return [];
    }

    /**
     * Get the data (location, order, pages) for a widget.
     *
     * @param string $name The widget to get data for.
     * @return object|null Widgets data.
     */
    protected static function getData(string $name): ?object {
        return DB::getInstance()->query('SELECT `location`, `order`, `pages` FROM nl2_widgets WHERE `name` = ?', [$name])->first();
    }

    /**
     * Render this widget to be displayed on a template page.
     * @return string Content/HTML of this widget.
     */
    public function display(): string {
        return $this->_content;
    }

}
