<?php

use Symfony\Component\HttpFoundation\Request;

/**
 * Two-Factor Authentication middleware hook.
 * Enforces TFA requirements for users in groups that require it.
 *
 * @package NamelessMC\Hooks
 * @author Aberdeener
 * @version 2.3.0
 * @license MIT
 */
class TFAMiddleware extends AbstractMiddleware
{
    public array $exemptRoutes = [
        '/logout',
        '/user/settings' // Allow access to settings to enable TFA
    ];

    public function handle(User $user, Request $request, Language $language): void
    {
        // Only process for logged-in users
        if (!$user->isLoggedIn()) {
            return;
        }

        // Skip if AJAX request, such as Alert or PM checks
        if ($request->isXmlHttpRequest()) {
            return;
        }

        // Check if any of the user's groups have TFA forced
        $forced_tfa = false;
        foreach ($user->getGroups() as $group) {
            if ($group->force_tfa) {
                $forced_tfa = true;
                break;
            }
        }

        // If TFA is forced and user doesn't have it enabled, redirect
        if ($forced_tfa && !$user->data()->tfa_enabled) {
            Session::put('force_tfa_alert', $language->get('user', 'force_tfa_alert'));
            Redirect::to(URL::build('/user/settings', 'do=enable_tfa'));
        }
    }
}
