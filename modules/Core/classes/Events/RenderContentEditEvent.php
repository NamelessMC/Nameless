<?php

class RenderContentEditEvent extends AbstractEvent {

    public string $content;
    public bool $skip_purify;

    public function __construct(string $content, bool $skip_purify = false) {
        $this->content = $content;
        $this->skip_purify = $skip_purify;
    }

    public static function name(): string {
        return 'renderContentEdit';
    }

    public static function description(): string {
        return 'renderContentEdit';
    }

    public static function internal(): bool {
        return true;
    }

    public static function return(): bool {
        return true;
    }
}