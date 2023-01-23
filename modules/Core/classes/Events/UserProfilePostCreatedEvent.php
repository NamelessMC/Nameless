<?php

class UserProfilePostCreatedEvent extends AbstractEvent {

    public static function description(): array {
        return ['admin', 'user_new_profile_post_hook_info'];
    }

    public static function name(): string {
        return 'userNewProfilePost';
    }

    public User $poster;
    public string $username;
    public User $profile_user;
    public string $content;
    public string $content_full;
    public string $avatar_url;
    public string $url;
    public Language $language;

    public function __construct(User $poster, User $profile_user, string $content, Language $language) {
        $this->poster = $poster;
        $this->username = $poster->data()->username;
        $this->profile_user = $profile_user;
        $this->content = $language->get('user', 'x_posted_on_y_profile', [
            'poster' => $poster->getDisplayname(),
            'user' => $profile_user->getDisplayname(),
        ]);
        $this->content_full = strip_tags(str_ireplace(['<br />', '<br>', '<br/>'], "\r\n", $content));
        $this->avatar_url = $poster->getAvatar(128, true);
        $this->url = URL::getSelfURL() . ltrim(URL::build('/profile/' . urlencode($profile_user->getDisplayname(true)) . '/#post-' . urlencode(DB::getInstance()->lastId())), '/');
        $this->language = $language;
    }
}
