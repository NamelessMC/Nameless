<?php

interface Widget {

    public function getName(): string;

    public function getLocation(): string;

    public function getSettings(): ?string;

    public function getDescription(): string;

    public function getModule(): string;

    public function getOrder(): ?int;

    public function getSmarty(): ?Smarty;

    public function display(): string;

    public function getPages(): array;

}
