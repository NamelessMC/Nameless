<?php
/**
 * hCaptcha class
 *
 * @package Modules\Core\Captcha
 * @author Samerton
 * @version 2.0.0-pr10
 * @license MIT
 */
class hCaptcha extends CaptchaBase {

    public function __construct(?string $privateKey, ?string $publicKey) {
        $this->_name = 'hCaptcha';
        $this->_privateKey = $privateKey;
        $this->_publicKey = $publicKey;
    }

    public function validateToken(array $post): bool {
        $token = $post['h-captcha-response'];

        $url = 'https://hcaptcha.com/siteverify';

        $result = HttpClient::post($url, [
            'secret' => $this->getPrivateKey(),
            'response' => $token,
        ])->json(true);

        return $result['success'] == 'true';
    }

    public function validateSecret(string $_secret) : bool {
        return true; // Haven't found a way to verify this
    }

    public function validateKey(string $key) : bool {
        return true; // hCaptcha changed their validation so this is a temporary fix
    }

    public function getHtml(): string {
        if (defined('DARK_MODE') && DARK_MODE == 1) {
            return '<div class="h-captcha" data-sitekey="' . $this->getPublicKey() . '" data-theme="dark"></div>';
        } else {
            return '<div class="h-captcha" data-sitekey="' . $this->getPublicKey() . '"></div>';
        }
    }

    public function getJavascriptSource(): string {
        return 'https://hcaptcha.com/1/api.js';
    }

    public function getJavascriptSubmit(string $id): ?string {
        return null;
    }
}
