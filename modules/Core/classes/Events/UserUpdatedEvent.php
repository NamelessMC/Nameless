<?php

class UserUpdatedEvent extends AbstractEvent implements HasWebhookParams {

    public User $user;
    public UserData $updated_data;

    public function __construct(User $user) {
        $this->user = $user;

        $data = DB::getInstance()->get('users', ['id', $user->data()->id]);
        $this->updated_data = new UserData($data->first());
    }

    public static function name(): string {
        return 'updatedUser';
    }

    public static function description(): string {
        return (new Language())->get('admin', 'user_updated_hook_info');
    }

    public function webhookParams(): array {
        return [
            'user_id' => $this->user->data()->id,
            'username' => $this->user->getDisplayname(),
            'profile_url' => URL::getSelfURL() . ltrim($this->user->getProfileURL(), '/')
        ];
    }
}
