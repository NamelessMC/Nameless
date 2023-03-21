<?php

class UserIntegrationVerifiedEvent extends AbstractEvent implements DiscordDispatchable {

    public User $user;
    public IntegrationBase $integration;
    public IntegrationUser $integration_user;

    public function __construct(IntegrationUser $integration_user) {
        $this->user = $integration_user->getUser();
        $this->integration = $integration_user->getIntegration();
        $this->integration_user = $integration_user;
    }

    public static function name(): string {
        return 'verifyIntegrationUser';
    }

    public static function description(): string {
        return (new Language())->get('admin', 'user_verify_integration_hook_info');
    }

    public function toDiscordWebhook(): DiscordWebhookBuilder {
        $language = new Language('core', DEFAULT_LANGUAGE);

        return DiscordWebhookBuilder::make()
            ->setUsername($this->user->getDisplayname() . ' | ' . SITE_NAME)
            ->setAvatarUrl($this->user->getAvatar(128, true))
            ->addEmbed(function (DiscordEmbed $embed) use ($language) {
                return $embed
                    ->setDescription($language->get('user', 'user_has_verified_integration', [
                        'user' => $this->user->getDisplayname(),
                        'integration' => $this->integration->getName(),
                    ]))
                    ->setUrl(URL::getSelfURL() . ltrim($this->user->getProfileURL(), '/'));
            });
    }
}
