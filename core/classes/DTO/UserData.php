<?php
/**
 * Represents data which belongs to a user.
 *
 * @package NamelessMC\DTO
 * @author Aberdeener
 * @version 2.0.0-pr13
 * @license MIT
 */
class UserData {

    public int $id;
    public string $username;
    public string $nickname;
    public string $password;
    public string $pass_method;
    public int $joined;
    public string $email;
    public bool $isbanned;
    public string $lastip;
    public bool $active;
    public ?string $signature;
    public int $profile_views;
    public int $reputation;
    public ?string $reset_code;
    public bool $has_avatar;
    public bool $gravatar;
    public bool $topic_updates;
    public bool $private_profile;
    public ?int $last_online;
    public ?string $user_title;
    public ?int $theme_id;
    public ?int $language_id;
    public int $warning_points;
    public ?bool $night_mode;
    public bool $tfa_enabled;
    public int $tfa_type;
    public ?string $tfa_secret;
    public bool $tfa_complete;
    public ?string $banner;
    public string $timezone;
    public ?string $avatar_updated;
    public ?string $register_method;
    public bool $authme_sync_password;

    public function __construct(object $row) {
        $this->id = $row->id;
        $this->username = $row->username;
        $this->nickname = $row->nickname;
        $this->password = $row->password;
        $this->pass_method = $row->pass_method;
        $this->joined = $row->joined;
        $this->email = $row->email;
        $this->isbanned = $row->isbanned;
        $this->lastip = $row->lastip;
        $this->active = $row->active;
        $this->signature = $row->signature;
        $this->profile_views = $row->profile_views;
        $this->reputation = $row->reputation;
        $this->reset_code = $row->reset_code;
        $this->has_avatar = $row->has_avatar;
        $this->gravatar = $row->gravatar;
        $this->topic_updates = $row->topic_updates;
        $this->private_profile = $row->private_profile;
        $this->last_online = $row->last_online;
        $this->user_title = $row->user_title;
        $this->theme_id = $row->theme_id;
        $this->language_id = $row->language_id;
        $this->warning_points = $row->warning_points;
        $this->night_mode = $row->night_mode;
        $this->tfa_enabled = $row->tfa_enabled;
        $this->tfa_type = $row->tfa_type;
        $this->tfa_secret = $row->tfa_secret;
        $this->tfa_complete = $row->tfa_complete;
        $this->banner = $row->banner;
        $this->timezone = $row->timezone;
        $this->avatar_updated = $row->avatar_updated;
        $this->register_method = $row->register_method;
        $this->authme_sync_password = $row->authme_sync_password;
    }

}
