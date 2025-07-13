<?php

/**
 * Maintenance Mode middleware hook.
 * Redirects non-admin users when maintenance mode is enabled.
 *
 * @package NamelessMC\Hooks
 * @author Aberdeener
 * @version 2.3.0
 * @license MIT
 */
class MaintenanceModeMiddleware extends AbstractMiddleware
{
    public array $exemptRoutes = [
        '/maintenance',
        '/login',
        '/forgot_password',
        '/api',
        '/queries',
        '/oauth',
        '/store/listener',
    ];

    public function handle(User $user): void
    {
        // Check if maintenance mode is enabled
        if (!Settings::get('maintenance')) {
            return;
        }

        // Allow admin users to bypass maintenance mode
        if ($user->isLoggedIn() && $user->canViewStaffCP()) {
            // Display notice to admin stating maintenance mode is enabled
            define('BYPASS_MAINTENANCE', true);
            return;
        }

        Redirect::to(URL::build('/maintenance'));
    }
}
