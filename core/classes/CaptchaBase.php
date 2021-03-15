<?php
/*
 *	Made by Samerton
 *
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr10
 *
 *  License: MIT
 *
 *  CaptchaBase class
 */
abstract class CaptchaBase {
    protected static $_providers = array(), $_activeProvider = '';

    protected $_name, $_publicKey, $_privateKey;

    /**
     * Register a provider
     * @param CaptchaBase $provider Provider instance to register
     */
    public static function addProvider(CaptchaBase $provider) {
        self::$_providers[$provider->_name] = $provider;
    }

    /**
     * Get provider name
     * @return string Provider name
     */
    public function getName() {
        return $this->_name;
    }

    /**
     * Get public key
     * @return string Provider name
     */
    public function getPublicKey() {
        return $this->_publicKey;
    }

    /**
     * Get private key
     * @return string Provider name
     */
    public function getPrivateKey() {
        return $this->_privateKey;
    }

    /**
     * Set active provider
     * @param string $provider Provider name to set as active
     */
    public static function setActiveProvider($provider) {
        self::$_activeProvider = $provider;
    }

    /**
     * Return active provider
     * @return CaptchaBase Active provider
     */
    public static function getActiveProvider() {
        return self::$_providers[self::$_activeProvider];
    }

    /**
     * Return all providers
     * @return CaptchaBase[] Active provider
     */
    public static function getAllProviders() {
        return self::$_providers;
    }

    /**
     * Validate a Captcha token
     * @param array $post Post body to validate
     * @return boolean Whether the token was valid or not
     */
    public abstract function validateToken($post);

    /**
     * Get form input HTML to display
     * @return string|null HTML to display
     */
    public abstract function getHtml();

    /**
     * Get JavaScript source URL
     * @return string JS source URL
     */
    public abstract function getJavascriptSource();

    /**
     * Get JavaScript on submit function
     * @param string $id ID attribute of form
     * @return string|null JS for submit function
     */
    public abstract function getJavascriptSubmit($id);
}
