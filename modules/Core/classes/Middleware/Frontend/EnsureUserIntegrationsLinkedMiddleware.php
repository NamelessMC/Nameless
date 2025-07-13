<?php

class EnsureUserIntegrationsLinkedMiddleware extends AbstractMiddleware
{
    public MiddlewareType $type = MiddlewareType::Frontend;

    public array $exemptRoutes = [
        '/user/connections',
        '/oauth',
        '/user/settings',
    ];

    public function handle(User $user, Language $language): void
    {
        if (!$user->isLoggedIn()) {
            return;
        }

        // Check if any integrations is required before user can continue
        foreach (Integrations::getInstance()->getEnabledIntegrations() as $integration) {
            if ($integration->data()->required && $integration->allowLinking()) {
                $integrationUser = $user->getIntegration($integration->getName());
                if ($integrationUser === null || !$integrationUser->isVerified()) {
                    Session::flash('connections_error', $language->get('user', 'integration_required_to_continue'));
                    Redirect::to(URL::build('/user/connections'));
                }
            }
        }
    }
}
