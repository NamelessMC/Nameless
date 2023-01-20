<?php
/**
 * Base class integrations need to extend.
 *
 * @package NamelessMC\Integrations
 * @author Partydragen
 * @version 2.1.0
 * @license MIT
 */

abstract class IntegrationBase {

    private DB $_db;
    private IntegrationData $_data;
    protected string $_icon;
    private array $_errors = [];
    protected Language $_language;
    protected ?string $_settings = null;

    protected string $_name;
    protected ?int $_order;

    public function __construct() {
        $this->_db = DB::getInstance();

        $integration = $this->_db->query('SELECT * FROM nl2_integrations WHERE name = ?', [$this->_name]);
        if ($integration->count()) {
            $integration = $integration->first();

            $this->_data = new IntegrationData($integration);
            $this->_order = $integration->order;
        } else {
            // Register integration to database
            $this->_db->query('INSERT INTO nl2_integrations (name) VALUES (?)', [
                $this->_name
            ]);

            $integration = $this->_db->query('SELECT * FROM nl2_integrations WHERE name = ?', [$this->_name])->first();

            $this->_data = new IntegrationData($integration);
            $this->_order = $integration->order;
        }
    }

    /**
     * Get the name of this integration.
     *
     * @return string Name of integration.
     */
    public function getName(): string {
        return $this->_name;
    }

    /**
     * Get the icon of this integration.
     *
     * @return string Icon of integration.
     */
    public function getIcon(): string {
        return $this->_icon;
    }

    /**
     * Get the settings path for this integration.
     *
     * @return string Integration settings path.
     */
    public function getSettings(): ?string {
        return $this->_settings;
    }

    /**
     * Get if this integration is enabled
     *
     * @return bool Check if integration is enabled
     */
    public function isEnabled(): bool {
        return $this->data()->enabled;
    }

    /**
     * Get the display order of this integration.
     *
     * @return int Display order of integration.
     */
    public function getOrder(): ?int {
        return $this->_order;
    }

    /**
     * Get the integration data.
     *
     * @return IntegrationData This integration's data.
     */
    public function data(): IntegrationData {
        return $this->_data;
    }

    /**
     * Add an error to the errors array
     *
     * @param string $error The error message
     */
    public function addError(string $error): void {
        $this->_errors[] = $error;
    }

    /**
     * Get any errors from the functions given by this integration
     *
     * @return array Any errors
     */
    public function getErrors(): array {
        return $this->_errors;
    }

    /**
     * Get language
     *
     * @return Language Get language
     */
    public function getLanguage(): Language {
        return $this->_language;
    }

    /**
     * Should we allow linking with this integration?
     *
     * @return bool Whether to allow linking with this integration
     */
    public function allowLinking(): bool {
        return true;
    }

    /**
     * Called when user wants to link their account from user connections page, Does not need to be verified
     */
    abstract public function onLinkRequest(User $user);

    /**
     * Called when user wants to continue to verify their integration user from connections page
     */
    abstract public function onVerifyRequest(User $user);

    /**
     * Called when user wants to unlink their integration user from connections page
     */
    abstract public function onUnlinkRequest(User $user);

    /**
     * Called when the user have successfully validated the ownership of the account
     */
    abstract public function onSuccessfulVerification(IntegrationUser $integrationUser);

    /**
     * Validate username when it being linked or updated.
     *
     * @param string $username The username value to validate.
     * @param int $integration_user_id The integration user id to ignore during duplicate check.
     *
     * @return bool Whether this validation passed or not.
     */
    abstract public function validateUsername(string $username, int $integration_user_id = 0): bool;

    /**
     * Validate identifier when it being linked or updated.
     *
     * @param string $identifier The identifier value to validate.
     * @param int $integration_user_id The integration user id to ignore during duplicate check.
     *
     * @return bool Whether this validation passed or not.
     */
    abstract public function validateIdentifier(string $identifier, int $integration_user_id = 0): bool;

    /**
     * Called when register page being loaded
     */
    abstract public function onRegistrationPageLoad(Fields $fields);

    /**
     * Called before registration validation
     */
    abstract public function beforeRegistrationValidation(Validate $validate);

    /**
     * Called after registration validation
     */
    abstract public function afterRegistrationValidation();

    /**
     * Called when user is successfully registered
     */
    abstract public function successfulRegistration(User $user);

    /**
     * Called when user integration is requested to be synced.
     */
    abstract public function syncIntegrationUser(IntegrationUser $integration_user): bool;
}
