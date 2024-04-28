<?php

class GenerateNotificationContentEvent extends AbstractEvent {

    public string $content;
    public bool $skip_purify;
    public string $title;
    public User $user;

    public function __construct(string $content, string $title, User $user, bool $skip_purify = false) {
        $this->content = $content;
        $this->skip_purify = $skip_purify;
        $this->title = $title;
        $this->user = $user;
    }

    public static function name(): string {
        return 'generateNotificationContent';
    }

    public static function description(): string {
        return (new Language())->get('admin', 'generate_notification_content_hook_info');
    }

    public static function internal(): bool {
        return true;
    }

    public static function return(): bool {
        return true;
    }
}
