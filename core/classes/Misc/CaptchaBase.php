<?php
/**
 * Base class Captcha providers should extend.
 *
 * @package NamelessMC\Misc
 * @author Samerton
 * @version 2.0.0-pr10
 * @license MIT
 */
abstract class CaptchaBase {

    protected static array $_providers = [];
    protected static string $_activeProvider = '';

    protected string $_name;
    protected ?string $_publicKey;
    protected ?string $_privateKey;

    /**
     * Register a provider
     *
     * @param CaptchaBase $provider Provider instance to register
     */
    public static function addProvider(CaptchaBase $provider): void {
        self::$_providers[$provider->_name] = $provider;
    }

    /**
     * Return active provider
     *
     * @return CaptchaBase Active provider
     */
    public static function getActiveProvider(): CaptchaBase {
        return self::$_providers[self::$_activeProvider];
    }

    /**
     * Set active provider
     *
     * @param string $provider Provider name to set as active
     */
    public static function setActiveProvider(string $provider): void {
        self::$_activeProvider = $provider;
    }

    /**
     * Return all providers
     *
     * @return CaptchaBase[] All providers
     */
    public static function getAllProviders(): iterable {
        return self::$_providers;
    }

    /**
     * Is captcha enabled for a given key?
     *
     * @param string $key Key to lookup in db, defaults to simply recaptcha (for register, contact pages etc)
     * @return bool Whether captcha is enabled or not
     */
    public static function isCaptchaEnabled(string $key = 'recaptcha'): bool {
        if (!Config::get('core/captcha')) {
            return false;
        }

        return DB::getInstance()->selectQuery('SELECT `value` FROM nl2_settings WHERE `name` = ?', [$key])->first()->value == 'true';
    }

    /**
     * Get provider name
     *
     * @return string Provider name
     */
    public function getName(): string {
        return $this->_name;
    }

    /**
     * Get public key
     *
     * @return string Public key
     */
    public function getPublicKey(): string {
        return $this->_publicKey;
    }

    /**
     * Get private key
     *
     * @return string Private key
     */
    public function getPrivateKey(): string {
        return $this->_privateKey;
    }

    /**
     * Validate a Captcha token
     *
     * @param array $post Post body to validate
     * @return bool Whether the token was valid or not
     */
    abstract public function validateToken(array $post): bool;

    /**
     * Get form input HTML to display
     *
     * @return string|null HTML to display
     */
    abstract public function getHtml(): ?string;

    /**
     * Get JavaScript source URL
     *
     * @return string JS source URL
     */
    abstract public function getJavascriptSource(): string;

    /**
     * Get JavaScript on submit function
     *
     * @param string $id ID attribute of form
     * @return string|null JS for submit function
     */
    abstract public function getJavascriptSubmit(string $id): ?string;
}
