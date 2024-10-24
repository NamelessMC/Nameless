<?php

namespace NamelessMC\Framework\Pages;

abstract class Middleware
{
    abstract public function handle(): void;
}