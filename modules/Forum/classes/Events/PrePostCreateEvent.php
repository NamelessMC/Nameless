<?php

class PrePostCreateEvent extends AbstractEvent {

    public string $content;
    public User $user;
    public string $alert_url;
    public string $mention_notification_type;
    public LanguageKey $mention_notification_title;

    public function __construct(string $content, User $user, string $alert_url, string $mention_notification_type, LanguageKey $mention_notification_title) {
        $this->content = $content;
        $this->user = $user;
        $this->alert_url = $alert_url;
        $this->mention_notification_type = $mention_notification_type;
        $this->mention_notification_title = $mention_notification_title;
    }

    public static function internal(): bool {
        return true;
    }
}