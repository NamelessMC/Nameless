<?php
declare(strict_types=1);

use DebugBar\DebugBarException;

/**
 * hCaptcha class
 *
 * @package Modules\Core\Captcha
 * @author Samerton
 * @version 2.0.0-pr10
 * @license MIT
 */
class hCaptcha extends CaptchaBase {

    /**
     * @param string|null $privateKey
     * @param string|null $publicKey
     */
    public function __construct(?string $privateKey, ?string $publicKey) {
        $this->_name = 'hCaptcha';
        $this->_privateKey = $privateKey;
        $this->_publicKey = $publicKey;
    }

    /**
     * Validate a Captcha token
     *
     * @param array $post Post body to validate
     *
     * @return bool Whether the token was valid or not
     * @throws DebugBarException
     */
    public function validateToken(array $post): bool {
        $token = $post['h-captcha-response'];

        $url = 'https://hcaptcha.com/siteverify';

        $result = HttpClient::post($url, [
            'secret' => $this->getPrivateKey(),
            'response' => $token,
        ])->json(true);

        return $result['success'] === 'true';
    }

    /**
     * Validate if the private key is valid
     *
     * @param string $secret The secret key to validate
     *
     * @return bool Whether the private key is valid or not
     */
    public function validateSecret(string $secret): bool {
        return true; // Haven't found a way to verify this
    }

    /**
     * Validate if the public key is valid or not
     *
     * @param string $key The public key to validate
     *
     * @return bool Whether the public key is valid or not
     */
    public function validateKey(string $key): bool {
        return true; // hCaptcha changed their validation so this is a temporary fix
    }

    /**
     * Get form input HTML to display
     *
     * @return string HTML to display
     */
    public function getHtml(): string {
        if (defined('DARK_MODE') && DARK_MODE === true) {
            return '<div class="h-captcha" data-sitekey="' . $this->getPublicKey() . '" data-theme="dark"></div>';
        }

        return '<div class="h-captcha" data-sitekey="' . $this->getPublicKey() . '"></div>';
    }

    /**
     * Get JavaScript source URL
     *
     * @return string JS source URL
     */
    public function getJavascriptSource(): string {
        return 'https://hcaptcha.com/1/api.js';
    }

    /**
     * Get JavaScript on submit function
     *
     * @param string $id ID attribute of form
     * @return string|null JS for submit function
     */
    public function getJavascriptSubmit(string $id): ?string {
        return null;
    }
}
