<?php

class ContentEditEvent extends AbstractEvent {

    public string $content;
    public User $user;

    public function __construct(string $content, User $user) {
        $this->content = $content;
        $this->user = $user;
    }

    public static function name(): string {
        return 'contentEdit';
    }

    public static function description(): string {
        return 'contentEdit';
    }

    public static function internal(): bool {
        return true;
    }
}