<?php
/**
 * Recaptcha3 class
 *
 * @package Modules\Core\Captcha
 * @author Samerton
 * @version 2.1.0
 * @license MIT
 */
class Recaptcha3 extends CaptchaBase {

    public function __construct(?string $privateKey, ?string $publicKey) {
        $this->_name = 'Recaptcha3';
        $this->_privateKey = $privateKey;
        $this->_publicKey = $publicKey;
    }

    public function validateToken(array $post): bool {
        $token = $post['recaptcha'];

        $url = 'https://www.google.com/recaptcha/api/siteverify';

        $result = HttpClient::post($url, [
            'secret' => $this->getPrivateKey(),
            'response' => $token,
        ])->json(true);

        return $result['success'] == 'true';
    }

    public function validateSecret(string $secret) : bool {
        return true;
    }

    public function validateKey(string $key) : bool {
        return true;
    }

    public function getHtml(): ?string {
        return null;
    }

    public function getJavascriptSource(): string {
        return 'https://www.google.com/recaptcha/api.js?render=' . $this->getPublicKey();
    }

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
