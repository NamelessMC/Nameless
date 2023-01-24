<?php

class UserGroupRemovedEvent extends AbstractEvent implements DiscordDispatchable {

    public User $user;
    public Group $group;

    public function __construct(User $user, Group $group) {
        $this->user = $user;
        $this->group = $group;
    }

    public static function description(): array {
        return ['admin', 'user_group_removed_hook_info'];
    }

    public function toDiscordWebook(): DiscordWebhookBuilder {
        $language = new Language('core', DEFAULT_LANGUAGE);

        return DiscordWebhookBuilder::make()
            ->username($this->user->getDisplayname() . ' | ' . SITE_NAME)
            ->avatarUrl($this->user->getAvatar(128, true))
            ->embed(function (DiscordEmbed $embed) use ($language) {
                return $embed
                    ->description($language->get('user', 'group_has_been_removed', [
                        'group' => "`" . $this->group->name . "`",
                        'user' => $this->user->getDisplayname(),
                    ]));
            });
    }
}
