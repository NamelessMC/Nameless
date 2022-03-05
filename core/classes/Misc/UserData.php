<?php

class UserData {

    public int $id;
    public string $username;
    public string $nickname;
    public string $password;
    public string $pass_method;
    public string $uuid;
    public int $joined;
    public string $email;

    /** @deprecated  */
    public bool $isbanned;
    public bool $is_banned;

    /** @deprecated  */
    public string $lastip;
    public string $last_ip;

    /** @deprecated  */
    public bool $active;
    public bool $is_active;

    public string $signature;
    public int $profile_views;
    public int $reputation;
    public ?string $reset_code;
    public bool $has_avatar;
    public bool $gravatar;
    public bool $topic_updates;
    public bool $private_profile;
    public int $last_online;
    public string $user_title;

    /** @deprecated  */
    public int $theme_id;
    public int $template_id;

    public int $language_id;
    public int $warning_points;
    public bool $night_mode;
    public int $last_username_update;
    public bool $tfa_enabled;
    public int $tfa_type; // 1 = QR code, 2 = Email?
    public string $tfa_secret;
    public bool $tfa_complete;
    public ?string $banner;
    public string $timezone;
    public ?int $avatar_updated;
    public ?int $discord_id;
    public ?string $discord_username;

    public function __construct(?object $data) {
        $this->id = $data->id;
        $this->username = $data->username;
        $this->nickname = $data->nickname;
        $this->password = $data->password;
        $this->pass_method = $data->pass_method;
        $this->uuid = $data->uuid;
        $this->joined = $data->joined;
        $this->email = $data->email;
        $this->isbanned = $data->isbanned;
        $this->is_banned = $data->isbanned;
        $this->lastip = $data->lastip;
        $this->last_ip = $data->lastip;
        $this->active = $data->active;
        $this->is_active = $data->active;
        $this->signature = $data->signature;
        $this->profile_views = $data->profile_views;
        $this->reputation = $data->reputation;
        $this->reset_code = $data->reset_code;
        $this->has_avatar = $data->has_avatar;
        $this->gravatar = $data->gravatar;
        $this->topic_updates = $data->topic_updates;
        $this->private_profile = $data->private_profile;
        $this->last_online = $data->last_online;
        $this->user_title = $data->user_title;
        $this->theme_id = $data->theme_id;
        $this->template_id = $data->template_id;
        $this->language_id = $data->language_id;
        $this->warning_points = $data->warning_points;
        $this->night_mode = $data->night_mode;
        $this->last_username_update = $data->last_username_update;
        $this->tfa_enabled = $data->tfa_enabled;
        $this->tfa_type = $data->tfa_type;
        $this->tfa_secret = $data->tfa_secret;
        $this->tfa_complete = $data->tfa_complete;
        $this->banner = $data->banner;
        $this->timezone = $data->timezone;
        $this->avatar_updated = $data->avatar_updated;
        $this->discord_id = $data->discord_id;
        $this->discord_username = $data->discord_username;
    }

}
