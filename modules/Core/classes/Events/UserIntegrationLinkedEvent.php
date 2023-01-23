<?php

class UserIntegrationLinkedEvent extends AbstractEvent {

    public static function description(): array {
        return ['admin', 'user_link_integration_hook_info'];
    }

    public static function name(): string {
        return 'linkIntegrationUser';
    }

    public User $user;
    public int $user_id;
    public IntegrationBase $integration;
    public IntegrationUser $integration_user;
    public string $username;
    public string $content;
    public string $avatar_url;
    public string $url;

    public function __construct(IntegrationUser $integration_user, Language $language) {
        $this->user = $integration_user->getUser();
        $this->user_id = $this->user->data()->id;
        $this->integration = $integration_user->getIntegration();
        $this->username = $this->user->getDisplayname();
        $this->content = $language->get('user', 'user_has_linked_integration', [
            'user' => $this->user->getDisplayname(),
            'integration' => $integration_user->getIntegration()->getName(),
        ]);
        $this->avatar_url = $this->user->getAvatar(128, true);
        $this->url = URL::getSelfURL() . ltrim($this->user->getProfileURL(), '/');
        $this->integration_user = $integration_user;
    }
}
