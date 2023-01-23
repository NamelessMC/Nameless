<?php

class UserWarnedEvent extends AbstractEvent {

    public static function description(): array {
        return ['admin', 'warning_hook_info'];
    }

    public User $punished;
    public int $punished_id;
    public User $punisher;
    public int $punisher_id;
    public string $reason;

    public function __construct(User $punished, User $punisher, string $reason) {
        $this->punished = $punished;
        $this->punished_id = $punished->data()->id;
        $this->punisher = $punisher;
        $this->punisher_id = $punisher->data()->id;
        $this->reason = $reason;
    }
}
