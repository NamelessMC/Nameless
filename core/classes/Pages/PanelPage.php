<?php

namespace NamelessMC\Framework\Pages;

abstract class PanelPage extends Page {
    
    abstract public function permission(): string;

}