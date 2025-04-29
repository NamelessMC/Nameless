<?php

class PrePostEditEvent extends AbstractEvent {

    public string $content;
    public User $user;

    public function __construct(string $content, User $user) {
        $this->content = $content;
        $this->user = $user;
    }

    public static function name(): string {
        return 'prePostEdit';
    }

    public static function description(): string {
        return 'prePostEdit';
    }

    public static function internal(): bool {
        return true;
    }

    public static function return(): bool {
        return true;
    }
}