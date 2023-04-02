<?php

class UserGroupAddedEvent extends AbstractEvent implements HasWebhookParams, DiscordDispatchable {

    public User $user;
    public Group $group;

    public function __construct(User $user, Group $group) {
        $this->user = $user;
        $this->group = $group;
    }

    public static function description(): string {
        return (new Language())->get('admin', 'user_group_added_hook_info');
    }

    public function webhookParams(): array {
        return [
            'user_id' => $this->user->data()->id,
            'username' => $this->user->getDisplayname(),
            'group' => [
                'id' => $this->group->id,
                'name' => $this->group->name
            ]
        ];
    }

    public function toDiscordWebhook(): DiscordWebhookBuilder {
        $language = new Language('core', DEFAULT_LANGUAGE);

        return DiscordWebhookBuilder::make()
            ->setUsername($this->user->getDisplayname() . ' | ' . SITE_NAME)
            ->setAvatarUrl($this->user->getAvatar(128, true))
            ->addEmbed(function (DiscordEmbed $embed) use ($language) {
                return $embed
                    ->setDescription($language->get('user', 'group_has_been_added', [
                        'group' => "`" . $this->group->name . "`",
                        'user' => $this->user->getDisplayname(),
                    ]));
            });
    }
}
