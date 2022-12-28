<?php
declare(strict_types=1);

use DebugBar\DebugBarException;

/**
 * Recaptcha3 class
 *
 * @package Modules\Core\Captcha
 * @author Samerton
 * @version 2.0.0-pr10
 * @license MIT
 */
class Recaptcha3 extends CaptchaBase {

    /**
     * @param string|null $privateKey
     * @param string|null $publicKey
     */
    public function __construct(?string $privateKey, ?string $publicKey) {
        $this->_name = 'Recaptcha3';
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
        $token = $post['recaptcha'];

        $url = 'https://www.google.com/recaptcha/api/siteverify';

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
     * @throws DebugBarException
     */
    public function validateSecret(string $secret): bool {
        $token = "Verification";
        $url = 'https://www.google.com/recaptcha/api/siteverify';

        $result = HttpClient::post($url, [
            'secret' => $secret,
            'response' => $token
        ])->json(true);
        return !($result['error-codes'][0] === 'invalid-input-secret');
    }

    /**
     * Validate if the public key is valid or not
     *
     * @param string $key The public key to validate
     *
     * @return bool Whether the public key is valid or not
     * @throws DebugBarException
     */
    public function validateKey(string $key): bool {
        $url = 'https://www.google.com/recaptcha/api.js?render=' . $key;
        return str_contains(
            HttpClient::get($url)->contents(),
            $key
        );
    }

    /**
     * Get form input HTML to display
     *
     * @return string|null HTML to display
     */
    public function getHtml(): ?string {
        return null;
    }

    /**
     * Get JavaScript source URL
     *
     * @return string JS source URL
     */
    public function getJavascriptSource(): string {
        return 'https://www.google.com/recaptcha/api.js?render=' . $this->getPublicKey();
    }

    /**
     * Get JavaScript on submit function
     *
     * @param string $id ID attribute of form
     *
     * @return string JS for submit function
     */
    public function getJavascriptSubmit(string $id): string {
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
