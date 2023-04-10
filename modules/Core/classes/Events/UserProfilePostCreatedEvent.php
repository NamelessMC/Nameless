<?php

class UserProfilePostCreatedEvent extends AbstractEvent implements HasWebhookParams, DiscordDispatchable {

    public User $poster;
    public User $profile_user;
    public string $content;

    public function __construct(User $poster, User $profile_user, string $content) {
        $this->poster = $poster;
        $this->profile_user = $profile_user;
        $this->content = $content;
    }

    public static function name(): string {
        return 'userNewProfilePost';
    }

    public static function description(): string {
        return (new Language())->get('admin', 'user_new_profile_post_hook_info');
    }

    public function webhookParams(): array {
        return [
            'poster' => [
                'user_id' => $this->poster->data()->id,
                'username' => $this->poster->getDisplayname()
            ],
            'profile_user' => [
                'user_id' => $this->profile_user->data()->id,
                'username' => $this->profile_user->getDisplayname()
            ],
            'content' => $this->content,
            'url' => URL::getSelfURL() . ltrim(URL::build('/profile/' . urlencode($this->profile_user->getDisplayname(true)) . '/#post-' . urlencode(DB::getInstance()->lastId())), '/')
        ];
    }

    public function toDiscordWebhook(): DiscordWebhookBuilder {
        $language = new Language('core', DEFAULT_LANGUAGE);

        return DiscordWebhookBuilder::make()
            ->setUsername($this->poster->data()->username . ' | ' . SITE_NAME)
            ->setAvatarUrl($this->poster->getAvatar(128, true))
            ->addEmbed(function (DiscordEmbed $embed) use ($language) {
                return $embed
                    ->setDescription($language->get('user', 'x_posted_on_y_profile', [
                        'poster' => $this->poster->getDisplayname(),
                        'user' => $this->profile_user->getDisplayname(),
                    ]))
                    ->setUrl(URL::getSelfURL() . ltrim(URL::build('/profile/' . urlencode($this->profile_user->getDisplayname(true)) . '/#post-' . urlencode(DB::getInstance()->lastId())), '/'))
                    ->addField($language->get('general', 'content'), Text::embedSafe($this->content));
            });
    }
}
