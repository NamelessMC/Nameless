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

    private ?string $_raw_response;
    private ?array $_response;

    public function __construct(HttpClient $update_check_response) {
        $this->_raw_response = $update_check_response->contents();
        $this->_response = json_decode($this->_raw_response, true);
    }

    public function hasError(): bool {
        return $this->_response === null || !count($this->_response) || $this->_response['error'];
    }

    public function getErrorMessage(): string {
        if (isset($this->_response['message'])) {
            return 'Error from server: ' . $this->_response['message'];
        }

         return 'Invalid response from server: ' . $this->_raw_response;
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

    public function upgradeZipLink(): string {
        return $this->_response['upgrade_zip_link'];
    }

    public function gitHubLink(): string {
        return $this->_response['github_link'];
    }

    // TODO: @samerton
    public function checksum(): string {
        return $this->_response['checksum'];
    }
}
