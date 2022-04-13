<?php
/**
 * Base class integrations need to extend.
 *
 * @package NamelessMC\Integrations
 * @author Partydragen
 * @version 2.0.0-pr13
 * @license MIT
 */

abstract class IntegrationBase {

    private DB $_db;
    private $_data;
    private array $_errors = [];
    protected Language $_language;

    protected string $_name;
    protected ?int $_order;

    public function __construct() {
        $this->_db = DB::getInstance();

        $integration = $this->_db->selectQuery('SELECT * FROM nl2_integrations WHERE name = ?', [$this->_name]);
        if ($integration->count()) {
            $integration = $integration->first();

            $this->_data = $integration;
            $this->_order = $integration->order;
        } else {
            // Register integration to database
            $this->_db->createQuery('INSERT INTO nl2_integrations (name) VALUES (?)', [
                $this->_name
            ]);

            $integration = $this->_db->selectQuery('SELECT * FROM nl2_integrations WHERE name = ?', [$this->_name])->first();

            $this->_data = $integration;
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
     * @return object This integration's data.
     */
    public function data(): ?object {
        return $this->_data;
    }

    /**
     * Add a error to the errors array
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
}
