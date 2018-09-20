<?php
/*
*	Made by Samerton
*  https://github.com/NamelessMC/Nameless/
*  NamelessMC version 2.0.0-pr2
*
*  License: MIT
*
*  Email class
*/

class Email {
    // Send an email
    // Params:  $email - array containing all necessary email information to send as per the sendPHP and sendMailer functions
    //          $method - email sending method to use (php or mailer)
    public static function send($email, $method = 'php'){
        if($method == 'php')
            return self::sendPHP($email);
        else if($method == 'mailer')
            return self::sendMailer($email);
        else
            return false;
    }

    // Send an email using PHP's sendmail() function
    // Params: $email - array containing
    //                  - to = email address to send email to
    //                  - subject = subject line
    //                  - message = email contents
    //                  - headers = email headers
    private static function sendPHP($email){
        try {
            $mail = mail($email['to'], $email['subject'], $email['message'], $email['headers']);
            if($mail)
                return true;
            else {
                $error = error_get_last();
                if(isset($error['message']))
                    return array('error' => $error['message']);
                else
                    return array('error' => 'Unknown');
            }

        } catch(Exception $e){
            // Error
            return array('error' => $e->getMessage());
        }
        return false;
    }

    // Send an email using the PHPMailer library
    private static function sendMailer($email){
        require_once(ROOT_PATH . '/core/includes/phpmailer/PHPMailerAutoload.php');
        require_once(ROOT_PATH . '/core/email.php');

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

            if(isset($email['replyto']))
                $mail->AddReplyTo($email['replyto']['email'], $email['replyto']['name']);

            $mail->CharSet = "UTF-8";
			$mail->Encoding = "base64";
            $mail->setFrom($GLOBALS['email']['username'], $GLOBALS['email']['name']);
            $mail->From = $GLOBALS['email']['username'];
            $mail->FromName = $GLOBALS['email']['name'];
            $mail->addAddress($email['to']['email'], $email['to']['name']);
            $mail->Subject = $email['subject'];
            $mail->IsHTML(true);
            $mail->msgHTML($email['message']);
            $mail->Body = $email['message'];

            if(!$mail->send()){
                return array('error' => $mail->ErrorInfo);
            } else {
                return true;
            }
        } catch(phpmailerException $e){
            return array('error' => $e->getMessage());
        } catch(Exception $e){
            return array('error' => $e->getMessage());
        }
    }
}
