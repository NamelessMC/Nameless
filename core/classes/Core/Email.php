<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

/**
 * Handles sending emails and registering email placeholders.
 *
 * @package NamelessMC\Core
 * @author Samerton
 * @author Aberdeener
 * @version 2.0.0-pr13
 * @license MIT
 */
class Email {

    public const REGISTRATION = 1;
    public const FORGOT_PASSWORD = 3;
    public const API_REGISTRATION = 4;
    public const FORUM_TOPIC_REPLY = 5;
    public const MASS_MESSAGE = 6;

    /**
     * @var array<string, string> Placeholders for email templates
     */
    private static array $_message_placeholders = [];

    /**
     * Send an email.
     *
     * @param array $recipient Array containing `'email'` and `'name'` strings for the recipient of the email.
     * @param string $subject Subject of the email.
     * @param string $message Message of the email.
     * @param array $reply_to Array containing `'email'` and `'name'` strings for the reply-to address.
     * @return bool|array Returns true if email sent, otherwise returns an array containing the error.
     */
    public static function send(array $recipient, string $subject, string $message, array $reply_to) {
        $email = [
            'to' => $recipient,
            'subject' => $subject,
            'message' => $message,
            'replyto' => $reply_to,
        ];

        if (Util::getSetting('phpmailer') == '1') {
            return self::sendMailer($email);
        }

        return self::sendPHP($email);
    }

    /**
     * Get reply to array for send()
     * @return array Array with reply-to email address and name
     */
    public static function getReplyTo(): array {
        return [
            'email' => Util::getSetting('incoming_email'),
            'name' => SITE_NAME
        ];
    }

    /**
     * Send an email using PHP's `mail()` function.
     *
     * @param array $email Array containing `to`, `subject`, `message` and `headers` values.
     * @return array|bool Returns true if email sent, otherwise returns an array containing the error.
     */
    private static function sendPHP(array $email) {
        $outgoing_email = Util::getSetting('outgoing_email');
        $incoming_email = Util::getSetting('incoming_email');

        // TODO Handle non-ascii in subject and headers (RFC 1342)

        if (mail($email['to']['email'], $email['subject'], $email['message'], [
            'From' => SITE_NAME . ' ' . '<' . $outgoing_email . '>',
            'Reply-To' => $incoming_email,
            'MIME-Version' => '1.0',
            'Content-type' => 'text/html; charset=UTF-8'
        ])) {
            return true;
        }

        return [
            'error' => error_get_last()['message'] ?? 'Unknown error'
        ];
    }

    /**
     * Send an email using the PHPMailer library.
     *
     * @see PHPMailer
     *
     * @param array $email Array of email data to send.
     * @return array|bool Returns true if email sent, otherwise returns an array containing the error.
     */
    private static function sendMailer(array $email) {
        // Initialise PHPMailer
        $mail = new PHPMailer(true);

        try {
            // init
            $mail->IsSMTP();
            $mail->SMTPDebug = SMTP::DEBUG_OFF;
            $mail->Debugoutput = 'html';
            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';
            $mail->Timeout = 15;

            // login to their smtp account
            $mail->Host = Config::get('email.host', '');
            $mail->Port = Config::get('email.port', 587);
            $mail->SMTPSecure = Config::get('email.secure', 'tls');
            $mail->SMTPAuth = Config::get('email.smtp_auth', true);
            $mail->Username = Config::get('email.username', '');
            $mail->Password = Config::get('email.password', '');

            // set from email ("outgoing email" seting)
            $mail->setFrom(Config::get('email.email', ''), Config::get('email.name', ''));

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
     * Add a custom placeholder/variable for email messages.
     *
     * @param string $key The key to use for the placeholder, should be enclosed in square brackets.
     * @param string|Closure(Language, string): string $value The value to replace the placeholder with.
     */
    public static function addPlaceholder(string $key, $value): void {
        self::$_message_placeholders[$key] = $value;
    }

    /**
     * Format an email template and replace placeholders.
     *
     * @param string $email Name of email to format.
     * @param Language $viewing_language Instance of Language class to use for translations.
     * @return string Formatted email.
     */
    public static function formatEmail(string $email, Language $viewing_language): string {
        $placeholders = array_keys(self::$_message_placeholders);

        $placeholder_values = [];
        foreach (self::$_message_placeholders as $value) {
            if (is_callable($value)) {
                $placeholder_values[] = $value($viewing_language, $email);
            } else {
                $placeholder_values[] = $value;
            }
        }

        return str_replace(
            $placeholders,
            $placeholder_values,
            file_get_contents(implode(DIRECTORY_SEPARATOR, [ROOT_PATH, 'custom', 'templates', TEMPLATE, 'email', $email . '.html']))
        );
    }
}
