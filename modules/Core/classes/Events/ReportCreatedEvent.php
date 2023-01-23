<?php

class ReportCreatedEvent extends AbstractEvent {

    public static function description(): array {
        return ['admin', 'report_hook_info'];
    }

    public static function name(): string {
        return 'createReport';
    }

    public string $username;
    public string $content;
    public string $content_full;
    public string $avatar_url;
    public string $title;
    public string $url;

    public function __construct(
        string $username,
        string $content,
        string $content_full,
        string $avatar_url,
        string $title,
        string $url
    ) {
        $this->username = $username;
        $this->content = $content;
        $this->content_full = $content_full;
        $this->avatar_url = $avatar_url;
        $this->title = $title;
        $this->url = $url;
    }
}
