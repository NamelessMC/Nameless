<?php

class UserDeletedEvent extends AbstractEvent {

    public User $user;

    public function __construct(User $user) {
        $this->user = $user;
    }

    public static function name(): string {
        return 'deleteUser';
    }

    public static function description(): string {
        return (new Language())->get('admin', 'delete_hook_info');
    }
}
