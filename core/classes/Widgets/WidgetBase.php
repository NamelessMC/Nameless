<?php
/**
 * Base Widget class.
 *
 * @package NamelessMC\Misc
 * @author Samerton
 * @version 2.0.0-pr8
 * @license MIT
 */
abstract class WidgetBase extends AbstractWidget
{
    /**
     * Generate this widget's `$_content`.
     */
    abstract public function initialise(): void;

    public function getPages(): array
    {
        return $this->getData()->pages;
    }
}
