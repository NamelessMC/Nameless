<?php
/**
 * Contains reusable email functions for the Core module.
 *
 * @package Modules\Core\Misc
 * @author Aberdeener
 * @version 2.3.0
 * @license MIT
 */
class Core_Emails
{
    public static function sendRegisterEmail(Language $language, string $email_address, string $username, int $user_id, string $code): bool {
        $link = rtrim(URL::getSelfURL(), '/') . URL::build('/validate/', 'c=' . urlencode($code));

        $sent = Email::send(
            ['email' => $email_address, 'name' => $username],
            SITE_NAME . ' - ' . $language->get('emails', 'register_subject'),
            str_replace('[Link]', $link, Email::formatEmail('register', $language)),
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
}
