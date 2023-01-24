<?php

class AnnouncementCreatedEvent extends AbstractEvent implements DiscordDispatchable {

    public User $creator;
    public string $header;
    public string $message;

    public function __construct(User $creator, string $header, string $message) {
        $this->creator = $creator;
        $this->header = $header;
        $this->message = $message;
    }

    public static function name(): string {
        return 'createAnnouncement';
    }

    public static function description(): array {
        return ['admin', 'announcement_hook_info'];
    }

    public function toDiscordWebook(): DiscordWebhookBuilder {
        $language = new Language('core', DEFAULT_LANGUAGE);

        return DiscordWebhookBuilder::make()
            ->username($this->creator->getDisplayname() . ' | ' . SITE_NAME)
            ->avatarUrl($this->creator->getAvatar(128, true))
            ->embed(function (DiscordEmbed $embed) use ($language) {
                return $embed
                    ->title($language->get('admin', 'new_announcement') . ': ' . $this->header)
                    ->description(mb_strlen($this->message) > 512
                        ? mb_substr($this->message, 0, 512) . '...'
                        : $this->message
                    );
            });
    }
}
