<?php

class ContentCreateEvent extends AbstractEvent {

    public string $content;
    public User $user;

    public function __construct(string $content, User $user) {
        $this->content = $content;
        $this->user = $user;
    }

    public static function internal(): bool {
        return true;
    }
}