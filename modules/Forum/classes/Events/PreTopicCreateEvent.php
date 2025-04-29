<?php

class PreTopicCreateEvent extends AbstractEvent {

    public string $content;
    public User $user;
    public array $alert_full;
    public array $alert_short;
    public string $alert_url;

    public function __construct(string $content, User $user, array $alert_full, array $alert_short, string $alert_url) {
        $this->content = $content;
        $this->user = $user;
        $this->alert_full = $alert_full;
        $this->alert_short = $alert_short;
        $this->alert_url = $alert_url;
    }

    public static function name(): string {
        return 'preTopicCreate';
    }

    public static function description(): string {
        return 'preTopicCreate';
    }

    public static function internal(): bool {
        return true;
    }

    public static function return(): bool {
        return true;
    }
}