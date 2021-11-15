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
     *
     * @param string|null $privateKey
     * @param string|null $publicKey
     */
    public function __construct(?string $privateKey, ?string $publicKey) {
        $this->_name = 'Recaptcha3';
        $this->_privateKey = $privateKey;
        $this->_publicKey = $publicKey;
    }

    public function validateToken(array $post): bool {
        $token = $post['recaptcha'];

        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $post_data = 'secret=' . $this->getPrivateKey() . '&response=' . $token;

        $result = json_decode(HttpClient::post($url, $post_data)->data(), true);

        return $result['success'] == 'true';
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
