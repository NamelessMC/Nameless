<?php
declare(strict_types=1);

use GuzzleHttp\Exception\GuzzleException;

/**
 * Represents an integration user
 *
 * @package NamelessMC\Integrations
 * @author Partydragen
 * @version 2.0.0-pr13
 * @license MIT
 */
class IntegrationUser {


    /**
     * @var ?DB $_db ;
     */
    private static ?DB $_db;
    private IntegrationUserData $_data;
    private User $_user;
    private IntegrationBase $_integration;

    /**
     * @param IntegrationBase $integration
     * @param string|null $value
     * @param string $field
     * @param $query_data
     */
    public function __construct(IntegrationBase $integration, string $value = null, string $field = 'id', $query_data = null) {
        if (!isset(self::$_db)) {
            self::$_db = DB::getInstance();
        }
        $this->_integration = $integration;

        if (!$query_data && $value) {
            $field = preg_replace('/[^A-Za-z_]+/', '', $field);

            $data = self::$_db->query("SELECT * FROM nl2_users_integrations WHERE $field = ? AND integration_id = ?", [$value, $integration->data()->id]);
            if ($data->count()) {
                $this->_data = new IntegrationUserData($data->first());
            }
        } else if ($query_data) {
            // Load data from existing query.
            $this->_data = new IntegrationUserData($query_data);
        }
    }

    /**
     * Get the integration user data.
     *
     * @return IntegrationUserData This integration user data.
     */
    public function data(): IntegrationUserData {
        return $this->_data;
    }

    /**
     * Get the integration
     *
     * @return IntegrationBase Integration type for this user
     */
    public function getIntegration(): IntegrationBase {
        return $this->_integration;
    }

    /**
     * Does this integration user exist?
     *
     * @return bool Whether the user exists (has data) or not.
     */
    public function exists(): bool {
        return (!empty($this->_data));
    }

    /**
     * Get if this integration user is verified or not.
     *
     * @return bool Whether this integration user has been verified.
     */
    public function isVerified(): bool {
        return $this->data()->verified;
    }

    /**
     * Save a new user linked to a specific integration.
     *
     * @param User $user The user to link
     * @param string|null $identifier The id of the integration account
     * @param string|null $username The username of the integration account
     * @param bool $verified Verified the ownership of the integration account
     * @param string|null $code (optional) The verification code to verify the ownership
     * @throws GuzzleException
     */
    public function linkIntegration(User $user, ?string $identifier, ?string $username, bool $verified = false, string $code = null): void {
        self::$_db->query(
            'INSERT INTO nl2_users_integrations (user_id, integration_id, identifier, username, verified, date, code) VALUES (?, ?, ?, ?, ?, ?, ?)', [
                $user->data()->id,
                $this->_integration->data()->id,
                Output::getClean($identifier),
                Output::getClean($username),
                $verified ? 1 : 0,
                date('U'),
                $code
            ]
        );

        // Load the data for this integration from the query we just made
        $this->_data = new IntegrationUserData(self::$_db->query('SELECT * FROM nl2_users_integrations WHERE id = ?', [self::$_db->lastId()])->first());

        $default_language = new Language('core', DEFAULT_LANGUAGE);
        EventHandler::executeEvent('linkIntegrationUser', [
            'integration' => $this->_integration->getName(),
            'user_id' => $user->data()->id,
            'username' => $user->getDisplayName(),
            'content' => $default_language->get('user', 'user_has_linked_integration', [
                'user' => $user->getDisplayName(),
                'integration' => $this->_integration->getName(),
            ]),
            'avatar_url' => $user->getAvatar(128, true),
            'url' => URL::getSelfURL() . ltrim($user->getProfileURL(), '/'),
            'integration_user' => [
                'identifier' => $identifier,
                'username' => $username,
                'verified' => $verified,
            ]
        ]);
    }

    /**
     * Verify user integration
     * @throws GuzzleException
     */
    public function verifyIntegration(): void {
        $this->update([
            'verified' => true,
            'code' => null
        ]);

        $this->_integration->onSuccessfulVerification($this);

        $user = $this->getUser();
        $default_language = new Language('core', DEFAULT_LANGUAGE);
        EventHandler::executeEvent('verifyIntegrationUser', [
            'integration' => $this->_integration->getName(),
            'user_id' => $user->data()->id,
            'username' => $user->getDisplayName(),
            'content' => $default_language->get('user', 'user_has_verified_integration', [
                'user' => $user->getDisplayName(),
                'integration' => $this->_integration->getName(),
            ]),
            'avatar_url' => $user->getAvatar(128, true),
            'url' => URL::getSelfURL() . ltrim($user->getProfileURL(), '/'),
            'integration_user' => [
                'identifier' => $this->data()->identifier,
                'username' => $this->data()->username,
                'verified' => $this->data()->verified,
            ]
        ]);
    }

    /**
     * Update integration user data in the database.
     *
     * @param array $fields Column names and values to update.
     * @throws RuntimeException
     */
    public function update(array $fields = []): void {
        if (!self::$_db->update('users_integrations', $this->data()->id, $fields)) {
            throw new RuntimeException('There was a problem updating integration user.');
        }
    }

    /**
     * Get the NamelessMC User that belong to this integration user
     *
     * @return User NamelessMC User that belong to this integration user
     * @throws GuzzleException
     */
    public function getUser(): User {
        return $this->_user ??= new User($this->data()->user_id);
    }

    /**
     * Delete integration user data.
     * @throws GuzzleException
     */
    public function unlinkIntegration(): void {
        self::$_db->query(
            'DELETE FROM nl2_users_integrations WHERE user_id = ? AND integration_id = ?', [
                $this->data()->user_id,
                $this->_integration->data()->id
            ]
        );

        $user = $this->getUser();
        $default_language = new Language('core', DEFAULT_LANGUAGE);
        EventHandler::executeEvent('unlinkIntegrationUser', [
            'integration' => $this->_integration->getName(),
            'user_id' => $user->data()->id,
            'username' => $user->getDisplayName(),
            'content' => $default_language->get('user', 'user_has_unlinked_integration', [
                'user' => $user->getDisplayName(),
                'integration' => $this->_integration->getName(),
            ]),
            'avatar_url' => $user->getAvatar(128, true),
            'url' => URL::getSelfURL() . ltrim($user->getProfileURL(), '/'),
            'integration_user' => [
                'identifier' => $this->data()->identifier,
                'username' => $this->data()->username,
                'verified' => $this->data()->verified,
            ]
        ]);
    }
}
