<?php

class UserValidatedEvent extends AbstractEvent implements HasWebhookParams, DiscordDispatchable {

    public User $user;

    public function __construct(User $user) {
        $this->user = $user;
    }

    public static function name(): string {
        return 'validateUser';
    }

    public static function description(): string {
        return (new Language())->get('admin', 'validate_hook_info');
    }

    public function webhookParams(): array {
        return [
            'user_id' => $this->user->data()->id,
            'username' => $this->user->getDisplayname(),
            'profile_url' => URL::getSelfURL() . ltrim($this->user->getProfileURL(), '/')
        ];
    }

    public function toDiscordWebhook(): DiscordWebhookBuilder {
        $language = new Language('core', DEFAULT_LANGUAGE);

        return DiscordWebhookBuilder::make()
            ->setUsername(SITE_NAME)
            ->addEmbed(function (DiscordEmbed $embed) use ($language) {
                return $embed
                    ->setAuthor(
                        $this->user->getDisplayname(),
                        $this->user->getAvatar(128, true),
                        URL::getSelfURL() . ltrim($this->user->getProfileURL(), '/')
                    )
                    ->setDescription($language->get('user', 'user_x_has_validated', [
                            'user' => $this->user->getDisplayname(),
                    ]));
            });
    }
}
