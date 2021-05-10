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
    public static function send($email, $method = 'php') {
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
     */
    private static function sendPHP($email) {
        try {
            $mail = mail($email['to'], $email['subject'], $email['message'], $email['headers']);

            if ($mail) {
                return true;
            }
            
            $error = error_get_last();

            if (isset($error['message'])) {
                return array('error' => $error['message']);
            } else {
                return array('error' => 'Unknown');
            }
        
        } catch (Exception $e) {
            return array('error' => $e->getMessage());
        }

        return false;
    }

    /**
     * Send an email using the PHPMailer library.
     * 
     * @param array $email array of email to send.
     */
    private static function sendMailer($email) {
        require_once(ROOT_PATH . '/core/includes/phpmailer/PHPMailerAutoload.php');
        require(ROOT_PATH . '/core/email.php');

        // Initialise PHPMailer
        $mail = new PHPMailer(true);

        try {
            $mail->IsSMTP();
            $mail->SMTPDebug = 0;
            $mail->Debugoutput = 'html';
            $mail->Host = $GLOBALS['email']['host'];
            $mail->Port = $GLOBALS['email']['port'];
            $mail->SMTPSecure = $GLOBALS['email']['secure'];
            $mail->SMTPAuth = $GLOBALS['email']['smtp_auth'];
            $mail->Username = $GLOBALS['email']['username'];
            $mail->Password = $GLOBALS['email']['password'];

            if (isset($email['replyto'])) {
                $mail->AddReplyTo($email['replyto']['email'], $email['replyto']['name']);
            }

            $mail->CharSet = "UTF-8";
            $mail->Encoding = "base64";
            $mail->setFrom($GLOBALS['email']['email'], $GLOBALS['email']['name']);
            $mail->From = $GLOBALS['email']['email'];
            $mail->FromName = $GLOBALS['email']['name'];
            $mail->addAddress($email['to']['email'], $email['to']['name']);
            $mail->Subject = $email['subject'];
            $mail->IsHTML(true);
            $mail->msgHTML($email['message']);
            $mail->Body = $email['message'];

            if (!$mail->send()) {
                return array('error' => $mail->ErrorInfo);
            }

            return true;

        } catch (Exception $e) {
            return array('error' => $e->getMessage());
        }
    }

    /**
     * Format an email template and replace placeholders.
     * 
     * @param string $email Name of email to format.
     * @param Language $viewing_language Instance of Language class to use for translations.
     */
    public static function formatEmail($email, Language $viewing_language) {
        return str_replace(
            ['[Sitename]', '[Greeting]', '[Message]', '[Thanks]'],
            [SITE_NAME, $viewing_language->get('emails', 'greeting'), $viewing_language->get('emails', $email . '_message'), $viewing_language->get('emails', 'thanks')],
            file_get_contents(join(DIRECTORY_SEPARATOR, [ROOT_PATH, 'custom', 'templates', TEMPLATE, 'email', $email . '.html']))
        );
    }
}
