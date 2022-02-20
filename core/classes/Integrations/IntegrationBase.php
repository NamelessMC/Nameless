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

    abstract public function onLink(User $user);
    abstract public function onUnlink(User $user);
}