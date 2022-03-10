<?php
/*
 *	Made by Partydragen
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr13
 *
 *  License: MIT
 *
 *  Integration Base class
 */

abstract class IntegrationBase {

    private DB $_db;
    private $_data;
    private array $_errors = [];

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
     * @param string The error message
     */
    public function addError(string $error): void {
        $this->_errors[] = $error;
    }

    /**
     * Get any errors from the functions given by this integration
     *
     * @return array Return any errors
     */
    public function getErrors(): array {
        return $this->_errors;
    }

    abstract public function onLinkRequest(User $user);
    abstract public function onVerifyRequest(User $user);
    abstract public function onUnlinkRequest(User $user);
    abstract public function onSuccessfulVerification(IntegrationUser $integrationUser);
}