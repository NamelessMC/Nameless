<?php

class AnnouncementCreated extends AbstractEvent implements DiscordDispatchable {

    public static function description(): array {
        return ['admin', 'announcement_hook_info'];
    }

    public static function name(): string {
        return 'createAnnouncement';
    }

    public int $announcement_id;
    public string $username;
    public string $header;
    public string $message;
    public string $avatar_url;
    public Language $language;

    public function __construct(int $announcement_id, string $username, string $header, string $message, string $avatar_url, Language $language) {
        $this->announcement_id = $announcement_id;
        $this->username = $username;
        $this->header = $header;
        $this->message = $message;
        $this->avatar_url = $avatar_url;
        $this->language = $language;
    }

    public function toDiscordWebook(): DiscordWebhookBuilder {
        return DiscordWebhookBuilder::make()
            ->username($this->username . ' | ' . SITE_NAME)
            ->avatarUrl($this->avatar_url)
            ->embed(function (DiscordEmbed $embed) {
                $embed
                    ->title($this->language->get('admin', 'new_announcement') . ': ' . $this->header)
                    ->description(mb_strlen($this->message) > 512
                        ? mb_substr($this->message, 0, 512) . '...'
                        : $this->message
                    );
            });
    }
}
