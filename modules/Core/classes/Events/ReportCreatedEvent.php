<?php

class ReportCreatedEvent extends AbstractEvent implements HasWebhookParams, DiscordDispatchable {

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

    public static function description(): string {
        return (new Language())->get('admin', 'report_hook_info');
    }

    public function webhookParams(): array {
        return [
            'username' => $this->username,
            'title' => $this->title,
            'content' => $this->content,
            'content_full' => $this->content_full,
            'url' => $this->url
        ];
    }

    public function toDiscordWebhook(): DiscordWebhookBuilder {
        return DiscordWebhookBuilder::make()
            ->setUsername($this->username . ' | ' . SITE_NAME)
            ->setAvatarUrl($this->avatar_url)
            ->addEmbed(function (DiscordEmbed $embed) {
                return $embed
                    ->setTitle($this->title)
                    ->setUrl($this->url)
                    ->setDescription(mb_strlen($this->content) > 512
                        ? mb_substr($this->content, 0, 512) . '...'
                        : $this->content
                    );
            });
    }
}
