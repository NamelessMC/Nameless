<?php

class UserValidatedEvent extends AbstractEvent {

    public static function name(): string {
        return 'validateUser';
    }

    public User $user;
    public int $user_id;
    public string $username;
    public string $content;
    public string $avatar_url;
    public string $url;

    public function __construct(User $user, Language $language) {
        $this->user = $user;
        $this->user_id = $user->data()->id;
        $this->username = $user->getDisplayname();
        $this->content = $language->get('user', 'user_x_has_validated', [
            'user' => $user->getDisplayname(),
        ]);
        $this->avatar_url = $user->getAvatar(128, true);
        $this->url = URL::getSelfURL() . ltrim($user->getProfileURL(), '/');
    }

    public static function description(): array {
        return ['admin', 'validate_hook_info'];
    }
}
