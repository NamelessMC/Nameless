<?php
/*
*	Made by Samerton
*  https://github.com/NamelessMC/Nameless/
*  NamelessMC version 2.0.0-pr8
*
*  License: MIT
*
*  Email class
*/

class Email {

    /**
     * Send an email.
     * 
     * @param array $email Array containing all necessary email information to send as per the sendPHP and sendMailer functions.
     * @param string|null $method Email sending method to use (`php` or `mailer`). Uses `php` if not provided. 
     */
    public static function send(array $email, string $method = 'php') {
        if ($method == 'php') {
            return self::sendPHP($email);
        } 
        else if ($method == 'mailer') {
            return self::sendMailer($email);
        }
        else {
            return false;
        }
    }

    /**
     * Send an email using PHP's sendmail() function.
     * 
     * @param array $email Array containing `to`, `subject`, `message` and `headers` values.
     * 
     * @return array|bool
     */
    private static function sendPHP(array $email) {
        try {

            if (mail($email['to'], $email['subject'], $email['message'], $email['headers'])) {
                return true;
            }

            $error = error_get_last();

            return [
                'error' => $error['message'] ?? 'Unknown error'
            ];

        } catch (Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Send an email using the PHPMailer library.
     * 
     * @param array $email array of email to send.
     * 
     * @return array|bool
     */
    private static function sendMailer(array $email) {
        require(ROOT_PATH . '/core/email.php');

        // Initialise PHPMailer
        $mail = new \PHPMailer\PHPMailer\PHPMailer(true);

        try {
            // init
            $mail->IsSMTP();
            $mail->SMTPDebug = 0;
            $mail->Debugoutput = 'html';
            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';

            // login to their smtp account
            $mail->Host = $GLOBALS['email']['host'];
            $mail->Port = $GLOBALS['email']['port'];
            $mail->SMTPSecure = $GLOBALS['email']['secure'];
            $mail->SMTPAuth = $GLOBALS['email']['smtp_auth'];
            $mail->Username = $GLOBALS['email']['username'];
            $mail->Password = $GLOBALS['email']['password'];

            // set from email ("outgoing email" seting)
            $mail->setFrom($GLOBALS['email']['email'], $GLOBALS['email']['name']);

            // add a "to" address
            $mail->addAddress($email['to']['email'], $email['to']['name']);

            // add a "reply-to" address if applicable
            if (isset($email['replyto'])) {
                $mail->AddReplyTo($email['replyto']['email'], $email['replyto']['name']);
            }

            // set subject + html message content
            $mail->Subject = $email['subject'];
            $mail->msgHTML($email['message']);

            if ($mail->send()) {
                return true;
            }

            return [
                'error' => $mail->ErrorInfo
            ];

        } catch (Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Format an email template and replace placeholders.
     * 
     * @param string $email Name of email to format.
     * @param Language $viewing_language Instance of Language class to use for translations.
     */
    public static function formatEmail(string $email, Language $viewing_language): string {
        return str_replace(
            // TODO: let modules add their own placeholders here? :o
            [
                '[Sitename]',
                '[Greeting]',
                '[Message]',
                '[Thanks]',
            ],
            [
                SITE_NAME,
                $viewing_language->get('emails', 'greeting'),
                $viewing_language->get('emails', $email . '_message'),
                $viewing_language->get('emails', 'thanks'),
            ],
            file_get_contents(join(DIRECTORY_SEPARATOR, [ROOT_PATH, 'custom', 'templates', TEMPLATE, 'email', $email . '.html']))
        );
    }
}
