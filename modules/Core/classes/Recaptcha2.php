<?php
/*
 *	Made by Samerton
 *
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr10
 *
 *  License: MIT
 *
 *  Recaptcha2 class
 */
class Recaptcha2 extends CaptchaBase {
    /**
     * Recaptcha2 constructor
     * @param string $privateKey
     * @param string $publicKey
     */
    public function __construct($privateKey, $publicKey) {
        $this->_name = 'Recaptcha2';
        $this->_privateKey = $privateKey;
        $this->_publicKey = $publicKey;
    }

    public function validateToken($post) {
        $token = $post['g-recaptcha-response'];

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
        return '<div class="g-recaptcha" data-sitekey="' . $this->getPublicKey() . '"></div>';
    }

    public function getJavascriptSource() {
        return 'https://www.google.com/recaptcha/api.js';
    }

    public function getJavascriptSubmit($id) {
        return null;
    }
}
