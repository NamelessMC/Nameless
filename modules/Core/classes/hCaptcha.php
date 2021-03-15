<?php
/*
 *	Made by Samerton
 *
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr10
 *
 *  License: MIT
 *
 *  hCaptcha class
 */
class hCaptcha extends CaptchaBase {
    /**
     * hCaptcha constructor
     * @param string $privateKey
     * @param string $publicKey
     */
    public function __construct($privateKey, $publicKey) {
        $this->_name = 'hCaptcha';
        $this->_privateKey = $privateKey;
        $this->_publicKey = $publicKey;
    }

    public function validateToken($post) {
        $token = $post['h-captcha-response'];

        $url = 'https://hcaptcha.com/siteverify';
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
        return '<div class="h-captcha" data-sitekey="' . $this->getPublicKey() . '"></div>';
    }

    public function getJavascriptSource() {
        return 'https://hcaptcha.com/1/api.js';
    }

    public function getJavascriptSubmit($id) {
        return null;
    }
}
