<?php

class UserDeletedEvent extends AbstractEvent implements HasWebhookParams {

    public User $user;

    public function __construct(User $user) {
        $this->user = $user;
    }

    public static function name(): string {
        return 'deleteUser';
    }

    public function webhookParams(): array {
        return [
            'user_id' => $this->user->data()->id,
            'username' => $this->user->getDisplayname(),
        ];
    }

    public static function description(): string {
        return (new Language())->get('admin', 'delete_hook_info');
    }
}
