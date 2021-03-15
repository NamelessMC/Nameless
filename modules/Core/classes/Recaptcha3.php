<?php
/*
 *	Made by Samerton
 *
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr10
 *
 *  License: MIT
 *
 *  Recaptcha3 class
 */
class Recaptcha3 extends CaptchaBase {
    /**
     * Recaptcha3 constructor
     * @param string $privateKey
     * @param string $publicKey
     */
    public function __construct($privateKey, $publicKey) {
        $this->_name = 'Recaptcha3';
        $this->_privateKey = $privateKey;
        $this->_publicKey = $publicKey;
    }

    public function validateToken($post) {
        $token = $post['recaptcha'];

        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $post_data = 'secret=' . $this->getPrivateKey() . '&response=' . $token;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($ch);

        $result = json_decode($result, true);

        return $result['success'] == 'true';
    }

    public function getHtml() {
        return null;
    }

    public function getJavascriptSource() {
        return 'https://www.google.com/recaptcha/api.js?render=' . $this->getPublicKey();
    }

    public function getJavascriptSubmit($id) {
        return '
        grecaptcha.ready(function() {
          grecaptcha.execute("' . $this->getPublicKey() . '", { action: "submit" }).then(function(token) {
              $("#' . $id . '").prepend("<input type=\"hidden\" name=\"recaptcha\" value=\"" + token + "\">");
              $("#' . $id . '").off("submit").submit();
          });
        });
        ';
    }
}
