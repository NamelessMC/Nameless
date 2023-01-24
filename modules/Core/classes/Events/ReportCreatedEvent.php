<?php

class ReportCreatedEvent extends AbstractEvent implements DiscordDispatchable {

    public string $username;
    public string $content;
    public string $content_full;
    public string $avatar_url;
    public string $title;
    public string $url;

    // TODO refactor when report system is improved
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

    public static function name(): string {
        return 'createReport';
    }

    public static function description(): array {
        return ['admin', 'report_hook_info'];
    }

    public function toDiscordWebook(): DiscordWebhookBuilder {
        return DiscordWebhookBuilder::make()
            ->username($this->username . ' | ' . SITE_NAME)
            ->avatarUrl($this->avatar_url)
            ->embed(function (DiscordEmbed $embed) {
                $embed
                    ->title($this->title)
                    ->url($this->url)
                    ->description(mb_strlen($this->content) > 512
                        ? mb_substr($this->content, 0, 512) . '...'
                        : $this->content
                    );
            });
    }
}
