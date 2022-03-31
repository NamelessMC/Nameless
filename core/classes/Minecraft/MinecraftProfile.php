<?php
/**
 * Represents a Minecraft profile.
 *
 * @package NamelessMC\Minecraft
 * @see ProfileUtils
 * @author Daniel Fanara
 * @author Samerton
 * @version 2.0.0-pr13
 * @license MIT
 */
class MinecraftProfile {

    private string $_username;
    private string $_uuid;
    private array $_properties;

    /**
     * @param string $username The player's username.
     * @param string $uuid The player's UUID.
     * @param array $properties The player's properties specified on their Mojang profile.
     */
    public function __construct(string $username, string $uuid, array $properties = []) {
        $this->_username = $username;
        $this->_uuid = $uuid;
        $this->_properties = $properties;
    }

    /**
     * @return string The player's username.
     */
    public function getUsername(): string {
        return $this->_username;
    }

    /**
     * @return string The player's UUID.
     */
    public function getUUID(): string {
        return $this->_uuid;
    }

    /**
     * @return array The player's properties listed on their mojang profile.
     */
    public function getProperties(): array {
        return $this->_properties;
    }

    /**
     * @return array Returns an array with keys of 'properties, usernname and uuid'.
     */
    public function getProfileAsArray(): array {
        return [
            'username' => $this->_username,
            'uuid' => $this->_uuid,
            'properties' => $this->_properties
        ];
    }
}
