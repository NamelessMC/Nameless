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

    /**
     * @var array All registered captcha providers.
     */
    protected static array $_providers = [];

    /**
     * @var string The name of the active captcha provider.
     */
    protected static string $_activeProvider = '';

    /**
     * @var string Name of this captcha provider.
     */
    protected string $_name;

    /**
     * @var string|null This captcha providers public key.
     */
    protected ?string $_publicKey;

    /**
     * @var string|null This captcha providers private key.
     */
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
        if (!Config::get('core.captcha')) {
            return false;
        }

        return Util::getSetting($key) === '1';
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
     * Validate if the private key is valid
     *
     * @param string $secret The secret key to validate
     * @return bool Whether the private key is valid or not
     */
    abstract public function validateSecret(string $secret) : bool;

    /**
     * Validate if the public key is valid or not
     *
     * @param string $key The public key to validate
     * @return bool Whether the public key is valid or not
     */
    abstract public function validateKey(string $key) : bool;

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
