<?php

class PreTopicEditEvent extends AbstractEvent {

    public string $content;
    public User $user;

    public function __construct(string $content, User $user) {
        $this->content = $content;
        $this->user = $user;
    }

    public static function name(): string {
        return 'preTopicEdit';
    }

    public static function description(): string {
        return 'preTopicEdit';
    }

    public static function internal(): bool {
        return true;
    }

    public static function return(): bool {
        return true;
    }
}