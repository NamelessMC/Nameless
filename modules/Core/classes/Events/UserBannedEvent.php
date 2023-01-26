<?php

class UserBannedEvent extends AbstractEvent implements DiscordDispatchable {

    public User $punished;
    public User $punisher;
    public string $reason;
    public bool $ip_ban;

    public function __construct(User $punished, User $punisher, string $reason, bool $ip_ban) {
        $this->punished = $punished;
        $this->punisher = $punisher;
        $this->reason = $reason;
        $this->ip_ban = $ip_ban;
    }

    public static function description(): array {
        return ['admin', 'ban_hook_info'];
    }

    public function toDiscordWebook(): DiscordWebhookBuilder {
        $language = new Language('core', DEFAULT_LANGUAGE);

        return DiscordWebhookBuilder::make()
            ->setUsername($this->punished->getDisplayname() . ' | ' . SITE_NAME)
            ->setAvatarUrl($this->punished->getAvatar(128, true))
            ->addEmbed(function (DiscordEmbed $embed) use ($language) {
                return $embed
                    ->setDescription($language->get('admin', 'user_banned_webhook', [
                        'punished' => $this->punished->getDisplayname(),
                        'punisher' => $this->punisher->getDisplayname(),
                    ]))
                    ->addField($language->get('admin', 'reason'), $this->reason)
                    ->addField($language->get('moderator', 'ip_ban'), $this->ip_ban
                        ? $language->get('general', 'yes')
                        : $language->get('general', 'no')
                    );
            });
    }
}
