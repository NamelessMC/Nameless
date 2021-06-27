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
     * Is captcha enabled for a given key?
     * @param $key string Key to lookup in db, defaults to simply recaptcha (for register, contact pages etc)
     * @return boolean Whether captcha is enabled or not
     * @throws Exception If unable to query database
     */
    public static function isCaptchaEnabled($key = 'recaptcha') {
        if (!Config::get('core/captcha')) {
            return false;
        }

        return DB::getInstance()->query('SELECT `value` FROM nl2_settings WHERE `name` = ?', array($key))->first()->value == 'true';
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
