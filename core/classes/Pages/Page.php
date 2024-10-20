<?php

namespace NamelessMC\Framework\Pages;

abstract class Page {

    abstract public function pageName(): string;

    abstract public function viewFile(): string;
    
    abstract public function render();

}