<?php

class RenderContentEditEvent extends AbstractEvent {

    public string $content;

    public function __construct(string $content) {
        $this->content = $content;
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