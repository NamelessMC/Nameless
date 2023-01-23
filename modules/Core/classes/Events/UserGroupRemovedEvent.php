<?php

class UserGroupRemovedEvent extends AbstractEvent {

    public static function description(): array {
        return ['admin', 'user_group_removed_hook_info'];
    }

    public User $user;
    public string $username;
    public string $avatar_url;
    public Group $group;
    public int $group_id;
    public string $group_name;
    public Language $language; // TODO needed for discordwebhookformatter, remove when that is updated

    public function __construct(User $user, Group $group, Language $language) {
        $this->user = $user;
        $this->username = $user->data()->username;
        $this->avatar_url = $user->getAvatar(128, true);
        $this->group = $group;
        $this->group_id = $group->id;
        $this->group_name = $group->name;
        $this->language = $language;
    }
}
