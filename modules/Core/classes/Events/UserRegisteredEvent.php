<?php

class UserRegisteredEvent extends AbstractEvent implements DiscordDispatchable {

    public User $user;

    public function __construct(User $user) {
        $this->user = $user;
    }

    public static function name(): string {
        return 'registerUser';
    }

    public static function description(): array {
        return ['admin', 'register_hook_info'];
    }

    public function toDiscordWebook(): DiscordWebhookBuilder {
        $language = new Language('core', DEFAULT_LANGUAGE);

        return DiscordWebhookBuilder::make()
            ->username(SITE_NAME)
            ->embed(function (DiscordEmbed $embed) use ($language) {
                return $embed
                    ->author(
                        $this->user->getDisplayname(),
                        $this->user->getAvatar(128, true),
                        URL::getSelfURL() . ltrim($this->user->getProfileURL(), '/')
                    )
                    ->description($language->get('user', 'user_x_has_registered', [
                            'user' => $this->user->getDisplayname(),
                            'siteName' => SITE_NAME,
                    ]));
            });
    }
}
