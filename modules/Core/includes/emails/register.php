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
    $php_mailer = $queries->getWhere('settings', ['name', '=', 'phpmailer']);
    $php_mailer = $php_mailer[0]->value;

    $link = rtrim(Util::getSelfURL(), '/') . URL::build('/validate/', 'c=' . $code);

    if ($php_mailer == '1') {
        // PHP Mailer
        $email = [
            'to' => ['email' => Output::getClean($email_address), 'name' => Output::getClean($username)],
            'subject' => SITE_NAME . ' - ' . $language->get('emails', 'register_subject'),
            'message' => str_replace('[Link]', $link, Email::formatEmail('register', $language))
        ];

        $sent = Email::send($email, 'mailer');

        if (isset($sent['error'])) {
            // Error, log it
            $queries->create(
                'email_errors',
                [
                    'type' => 1, // 1 = registration
                    'content' => $sent['error'],
                    'at' => date('U'),
                    'user_id' => $user_id
                ]
            );

            return false;
        }

    } else {
        // PHP mail function
        $siteemail = $queries->getWhere('settings', ['name', '=', 'outgoing_email']);
        $siteemail = $siteemail[0]->value;

        $headers = 'From: ' . $siteemail . "\r\n" .
            'Reply-To: ' . $siteemail . "\r\n" .
            'X-Mailer: PHP/' . phpversion() . "\r\n" .
            'MIME-Version: 1.0' . "\r\n" .
            'Content-type: text/html; charset=UTF-8' . "\r\n";

        $email = [
            'to' => $email_address,
            'subject' => SITE_NAME . ' - ' . $language->get('emails', 'register_subject'),
            'message' => str_replace('[Link]', $link, Email::formatEmail('register', $language)),
            'headers' => $headers
        ];

        $sent = Email::send($email, 'php');

        if (isset($sent['error'])) {
            // Error, log it
            $queries->create(
                'email_errors',
                [
                    'type' => 1, // 1 = registration
                    'content' => $sent['error'],
                    'at' => date('U'),
                    'user_id' => $user_id
                ]
            );

            return false;
        }
    }

    return true;
}