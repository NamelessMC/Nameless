<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr9
 *
 *  License: MIT
 *
 *  Register email
 */

function sendRegisterEmail(Queries $queries, Language $language, string $email_address, string $username, int $user_id, string $code): bool {
    $link = rtrim(Util::getSelfURL(), '/') . URL::build('/validate/', 'c=' . urlencode($code));

    $sent = Email::send(
        ['email' => Output::getClean($email_address), 'name' => Output::getClean($username)],
        SITE_NAME . ' - ' . $language->get('emails', 'register_subject'),
        str_replace('[Link]', $link, Email::formatEmail('register', $language)),
        Email::getReplyTo($queries)
    );

    if (isset($sent['error'])) {
        $queries->create('email_errors', [
            'type' => Email::REGISTRATION,
            'content' => $sent['error'],
            'at' => date('U'),
            'user_id' => $user_id
        ]);

        return false;
    }

    return true;
}
