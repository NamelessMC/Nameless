<?php

class UserBannedEvent extends AbstractEvent {

    public static function description(): array {
        return ['admin', 'ban_hook_info'];
    }

    public User $punished;
    public int $punished_id;
    public User $punisher;
    public int $punisher_id;
    public string $reason;
    public bool $ip_ban;

    public function __construct(User $punished, User $punisher, string $reason, bool $ip_ban) {
        $this->punished = $punished;
        $this->punished_id = $punished->data()->id;
        $this->punisher = $punisher;
        $this->punisher_id = $punisher->data()->id;
        $this->reason = $reason;
        $this->ip_ban = $ip_ban;
    }
}
