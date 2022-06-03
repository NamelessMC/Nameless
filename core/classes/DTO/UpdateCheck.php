<?php
/**
 * Simple object to make checking result of an update check more consistent.
 *
 * @package NamelessMC\DTO
 * @author Aberdeener
 * @version 2.0.0-pr13
 * @license MIT
 */
class UpdateCheck {

    private array $_response;

    public function __construct(array $response) {
        $this->_response = $response;
    }

    public function hasError(): bool {
        return !count($this->_response) || $this->_response['error'];
    }

    public function getErrorMessage(): string {
        return $this->_response['message'] ?? 'Invalid response from server: ' . json_encode($this->_response);
    }

    public function updateAvailable(): bool {
        return $this->_response['update_available'];
    }

    public function isUrgent(): bool {
        return $this->_response['urgent'];
    }

    public function instructions(): string {
        return $this->_response['install_instructions'];
    }

    public function version(): string {
        return $this->_response['name'] . ' (' . $this->_response['version_tag'] . ')';
    }

    public function gitHubLink(): string {
        return $this->_response['github_link'];
    }

    // TODO: @samerton
    public function checksum(): string {
        return $this->_response['checksum'];
    }
}
