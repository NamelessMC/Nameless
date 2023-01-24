<?php

class UserDeletedEvent extends AbstractEvent {

    public User $user;
    public int $user_id;
    public string $email_address;

    public function __construct(User $user) {
        $this->user = $user;
        $this->user_id = $user->data()->id;
        $this->email_address = $user->data()->email;
    }

    public static function name(): string {
        return 'deleteUser';
    }

    public static function description(): array {
        return ['admin', 'delete_hook_info'];
    }
}
