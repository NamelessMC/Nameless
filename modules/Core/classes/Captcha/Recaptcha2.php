<?php
/**
 * Recaptcha2 class
 *
 * @package Modules\Core\Captcha
 * @author Samerton
 * @version 2.0.0-pr10
 * @license MIT
 */
class Recaptcha2 extends CaptchaBase {

    public function __construct(?string $privateKey, ?string $publicKey) {
        $this->_name = 'Recaptcha2';
        $this->_privateKey = $privateKey;
        $this->_publicKey = $publicKey;
    }

    public function validateToken(array $post): bool {
        $token = $post['g-recaptcha-response'];

        $url = 'https://www.google.com/recaptcha/api/siteverify';

        $result = HttpClient::post($url, [
            'secret' => $this->getPrivateKey(),
            'response' => $token,
        ])->json(true);

        return $result['success'] == 'true';
    }

    public function validateSecret(string $secret) : bool {
        $token = "Verification";
        $url = 'https://www.google.com/recaptcha/api/siteverify';

        $result = HttpClient::post($url, [
            'secret' => $secret,
            'response' => $token
        ])->json(true);
        return $result['error-codes'][0] !== 'invalid-input-secret';
    }

    public function validateKey(string $key) : bool {
        return true; // No way to verify
    }

    public function getHtml(): string {
        return '<div class="g-recaptcha" data-sitekey="' . $this->getPublicKey() . '"></div>';
    }

    public function getJavascriptSource(): string {
        return 'https://www.google.com/recaptcha/api.js';
    }

    public function getJavascriptSubmit(string $id): ?string {
        return null;
    }
}
