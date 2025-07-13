<?php

/**
 * Banned User middleware hook.
 * Handles user bans and IP bans enforcement.
 *
 * @package NamelessMC\Hooks
 * @author Aberdeener
 * @version 2.3.0
 * @license MIT
 */
class BannedUserMiddleware extends AbstractMiddleware
{
    public function handle(User $user, Language $language): void
    {
        if (($user->isLoggedIn() && $user->data()->isbanned) || DB::getInstance()->get('ip_bans', ['ip', HttpUtils::getRemoteAddress()])->exists()) {
            $user->logout();

            Session::flash('home_error', $language->get('user', 'you_have_been_banned'));
            Redirect::to(URL::build('/'));
        }
    }
}
