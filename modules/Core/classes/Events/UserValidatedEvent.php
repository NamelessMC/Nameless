<?php

class UserValidatedEvent extends AbstractEvent implements DiscordDispatchable {

    public User $user;

    public function __construct(User $user) {
        $this->user = $user;
    }

    public static function name(): string {
        return 'validateUser';
    }

    public static function description(): array {
        return ['admin', 'validate_hook_info'];
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
                    ->description($language->get('user', 'user_x_has_validated', [
                            'user' => $this->user->getDisplayname(),
                    ]));
            });
    }
}
