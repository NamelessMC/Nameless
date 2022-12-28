<?php
declare(strict_types=1);
/**
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.2
 *
 *  License: MIT
 *
 *  Log user out
 *
 * @var User $user
 * @var Language $language
 * @var Announcements $announcements
 * @var Smarty $smarty
 * @var Pages $pages
 * @var Cache $cache
 * @var Navigation $navigation
 * @var array $cc_nav
 * @var array $staffcp_nav
 * @var Widgets $widgets
 * @var TemplateBase $template
 * @var Language $forum_language
 */

if ($user->isLoggedIn()) {
    if (Input::exists()) {
        try {
            if (Token::check()) {
                Log::getInstance()->log(Log::Action('user/logout'));
                $user->admLogout();
                $user->logout();

                Session::flash('home', $language->get('user', 'successfully_logged_out'));
                die($language->get('general', 'log_out_complete', ['linkStart' => '<a href="' . URL::build('/') . '">', 'linkEnd' => '</a>']));
            }
        } catch (Exception $ignored) {
        }

        echo $language->get('general', 'invalid_token') . '<hr />';
    }

    echo '
    <form method="post" action="" id="logout">
      <input type="hidden" name="token" value="' . Token::get() . '">
    </form>
    <a href="javascript:void(0)" onclick="document.getElementById(\'logout\').submit();">' . $language->get('general', 'log_out_click') . '</a>
    ';

    return;
}

Redirect::to(URL::build('/'));
