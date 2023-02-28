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
     * @param array|null $reply_to Array containing `'email'` and `'name'` strings for the reply-to address,
     * if not provided the default setting will be used.
     * @return bool|array Returns true if email sent, otherwise returns an array containing the error.
     */
    public static function send(array $recipient, string $subject, string $message, ?array $reply_to = null) {
        $email = [
            'to' => $recipient,
            'subject' => $subject,
            'message' => $message,
            'replyto' => $reply_to ?? self::getReplyTo(),
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
        error_clear_last();

        $outgoing_email = Util::getSetting('outgoing_email');
        $incoming_email = $email['replyto']['email'];

        $encoded_subject = '=?UTF-8?B?' . base64_encode($email['subject']) . '?=';
        $encoded_message = base64_encode($email['message']);
        $encoded_from = '=?UTF-8?B?' . base64_encode(SITE_NAME) . '?= <' . $outgoing_email . '>';

        if (mail($email['to']['email'], $encoded_subject, $encoded_message, [
            'From' => $encoded_from,
            'Reply-To' => $incoming_email,
            'MIME-Version' => '1.0',
            'Content-type' => 'text/html; charset=UTF-8',
            'Content-Transfer-Encoding' => 'base64',
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
        try {
            // Initialise PHPMailer
            $mail = new PHPMailer(true);

            $mail->IsSMTP();
            $mail->SMTPDebug = SMTP::DEBUG_OFF;
            $mail->Debugoutput = 'html';
            $mail->CharSet = PHPMailer::CHARSET_UTF8;
            $mail->Encoding = PHPMailer::ENCODING_BASE64;
            $mail->Timeout = 15;

            // login to their smtp account
            $mail->Host = Config::get('email.host', '');
            // set to override the resolution of the server hostname
            $mail->Hostname = Config::get('email.hostname', '');
            // required to be set if they have a separate web server and mail server using the same hostname
            $mail->Helo = Config::get('email.helo', '');
            $mail->Port = Config::get('email.port', 587);
            $mail->SMTPSecure = Config::get('email.secure', PHPMailer::ENCRYPTION_STARTTLS);
            $mail->SMTPAuth = Config::get('email.smtp_auth', true);
            $mail->Username = Config::get('email.username', '');
            $mail->Password = Config::get('email.password', '');

            // set "from" email ("outgoing email" setting)
            $mail->setFrom(Config::get('email.email', ''), Config::get('email.name', ''));

            // add a "to" address
            $mail->addAddress($email['to']['email'], $email['to']['name']);

            // add a "reply-to" address ("incoming email" setting)
            $mail->AddReplyTo($email['replyto']['email'], $email['replyto']['name']);

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
