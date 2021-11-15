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
     *
     * @param string|null $privateKey
     * @param string|null $publicKey
     */
    public function __construct(?string $privateKey, ?string $publicKey) {
        $this->_name = 'hCaptcha';
        $this->_privateKey = $privateKey;
        $this->_publicKey = $publicKey;
    }

    public function validateToken(array $post): bool {
        $token = $post['h-captcha-response'];

        $url = 'https://hcaptcha.com/siteverify';
        $post_data = 'secret=' . $this->getPrivateKey() . '&response=' . $token;

        $result = json_decode(HttpClient::post($url, $post_data)->data(), true);

        return $result['success'] == 'true';
    }

    public function getHtml(): string {
        return '<div class="h-captcha" data-sitekey="' . $this->getPublicKey() . '"></div>';
    }

    public function getJavascriptSource(): string {
        return 'https://hcaptcha.com/1/api.js';
    }

    public function getJavascriptSubmit(string $id): ?string {
        return null;
    }
}
