<?php

class AlertTemplate
{
    public function __construct(
        public LanguageKey|string $title,
        public LanguageKey|string|null $content = null,
        public ?string $link = null,
    ) {
        if ($this->link === null && $this->content === null) {
            throw new InvalidArgumentException('Either link or content must be provided');
        }
    }
}
