<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.2
 *
 *  License: MIT
 *
 *  Log user out
 */

if ($user->isLoggedIn()) {
    if (Input::exists()) {
        if (Token::check()) {
            Log::getInstance()->log(Log::Action('user/logout'));
            $user->admLogout();
            $user->logout();

            Session::flash('home', $language->get('user', 'successfully_logged_out'));
            die($language->get('general', 'log_out_complete', ['linkStart' => '<a href="' . URL::build('/') . '">', 'linkEnd' => '</a>']));
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
