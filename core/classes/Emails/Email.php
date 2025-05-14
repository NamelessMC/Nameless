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
class Email
{
    public const EMAIL_MAX_LENGTH = 75000;

    public const TEST_EMAIL = 'TestEmail';
    public const MASS_MESSAGE = 'MassMessage';

    public static function send(User $recipient, EmailTemplate $emailTemplate)
    {
        $languageCode = DB::getInstance()->get('languages', ['id', '=', $recipient->data()->language_id])->first()->short_code;

        return self::sendInternal(
            str_replace('EmailTemplate', '', $emailTemplate::class),
            $recipient,
            $emailTemplate->subject()->translate($languageCode),
            $emailTemplate->renderContent($languageCode)
        );
    }

    public static function sendRaw(string $mailer, User $recipient, string $subject, string $content)
    {
        return self::sendInternal($mailer, $recipient, $subject, $content);
    }

    /**
     * Internal helper method to handle common email sending logic.
     *
     * @param  string     $mailer    Email mailer identifier
     * @param  User       $recipient Recipient user object
     * @param  string     $subject   Email subject
     * @param  string     $content   Email content
     * @return bool|array Returns true if email sent, otherwise returns an array containing the error
     */
    private static function sendInternal(string $mailer, User $recipient, string $subject, string $content)
    {
        $email = [
            'to' => [
                'email' => $recipient->data()->email,
                'name' => $recipient->getDisplayname(),
            ],
            'subject' => SITE_NAME . ' - ' . $subject,
            'message' => $content,
            'replyto' => self::getReplyTo(),
        ];

        $result = Settings::get('phpmailer') == '1'
            ? self::sendMailer($email)
            : self::sendPHP($email);

        if (isset($result['error'])) {
            DB::getInstance()->insert('email_errors', [
                'mailer' => $mailer,
                'content' => $result['error'],
                'at' => date('U'),
                'user_id' => $recipient->data()->id,
            ]);
        }

        return $result;
    }

    /**
     * Get reply to array for send().
     * @return array Array with reply-to email address and name
     */
    public static function getReplyTo(): array
    {
        return [
            'email' => Settings::get('incoming_email'),
            'name' => SITE_NAME,
        ];
    }

    /**
     * Send an email using PHP's `mail()` function.
     *
     * @param  array      $email Array containing `to`, `subject`, `message` and `headers` values.
     * @return array|bool Returns true if email sent, otherwise returns an array containing the error.
     */
    private static function sendPHP(array $email)
    {
        error_clear_last();

        $outgoing_email = Settings::get('outgoing_email');
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
            'error' => error_get_last()['message'] ?? 'Unknown error',
        ];
    }

    /**
     * Send an email using the PHPMailer library.
     *
     * @see PHPMailer
     *
     * @param  array      $email Array of email data to send.
     * @return array|bool Returns true if email sent, otherwise returns an array containing the error.
     */
    private static function sendMailer(array $email)
    {
        try {
            $mail = new PHPMailer(true);

            $mail->IsSMTP();
            $mail->SMTPDebug = SMTP::DEBUG_OFF;
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
                'error' => $mail->ErrorInfo,
            ];
        } catch (Exception $e) {
            return [
                'error' => $e->getMessage(),
            ];
        }
    }
}
