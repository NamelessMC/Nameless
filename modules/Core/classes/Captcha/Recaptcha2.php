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
     *
     * @param string|null $privateKey
     * @param string|null $publicKey
     */
    public function __construct(?string $privateKey, ?string $publicKey) {
        $this->_name = 'Recaptcha2';
        $this->_privateKey = $privateKey;
        $this->_publicKey = $publicKey;
    }

    public function validateToken(array $post): bool {
        $token = $post['g-recaptcha-response'];

        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $post_data = 'secret=' . $this->getPrivateKey() . '&response=' . $token;

        $result = json_decode(HttpClient::post($url, $post_data), true);

        return $result['success'] == 'true';
    }

    public function getHtml(): string {
        return '<div class="g-recaptcha" data-sitekey="' . $this->getPublicKey() . '"></div>';
    }

    public function getJavascriptSource(): string {
        return 'https://www.google.com/recaptcha/api.js';
    }

    public function getJavascriptSubmit(string $id) {
        return null;
    }
}
