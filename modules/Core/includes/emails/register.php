<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr9
 *
 *  License: MIT
 *
 *  Register email
 */

function sendRegisterEmail(Language $language, string $email_address, string $username, int $user_id, string $code): bool {
    $link = rtrim(Util::getSelfURL(), '/') . URL::build('/validate/', 'c=' . urlencode($code));

    $sent = Email::send(
        ['email' => Output::getClean($email_address), 'name' => Output::getClean($username)],
        Output::getClean(SITE_NAME) . ' - ' . $language->get('emails', 'register_subject'),
        str_replace('[Link]', $link, Email::formatEmail('register', $language)),
        Email::getReplyTo()
    );

    if (isset($sent['error'])) {
        DB::getInstance()->insert('email_errors', [
            'type' => Email::REGISTRATION,
            'content' => $sent['error'],
            'at' => date('U'),
            'user_id' => $user_id
        ]);

        return false;
    }

    return true;
}
