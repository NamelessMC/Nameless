<?php
/**
 * Represents a integration user
 *
 * @package NamelessMC\Integrations
 * @author Partydragen
 * @version 2.0.0-pr13
 * @license MIT
 */
class IntegrationUser {

    private DB $_db;
    private $_data;
    private User $_user;
    private IntegrationBase $_integration;

    public function __construct(IntegrationBase $integration, string $value = null, string $field = 'id', $query_data = null) {
        $this->_db = DB::getInstance();
        $this->_integration = $integration;

        if (!$query_data && $value) {
            $data = $this->_db->selectQuery('SELECT * FROM nl2_users_integrations WHERE ' . $field . ' = ? AND integration_id = ?;', [$value, $integration->data()->id]);
            if ($data->count()) {
                $this->_data = $data->first();
            }
        } else if ($query_data) {
            // Load data from existing query.
            $this->_data = $query_data;
        }
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
     * Get the NamelessMC User that belong to this integration user
     *
     * @return User NamelessMC User that belong to this integration user
     */
    public function getUser(): User {
        return $this->_user ??= (function (): User {
            return new User($this->data()->user_id);
        })();
    }

    /**
     * Get the integration user data.
     *
     * @return object This integration user data.
     */
    public function data(): ?object {
        return $this->_data;
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
     * Update integration user data in the database.
     *
     * @param array $fields Column names and values to update.
     * @throws Exception
     */
    public function update(array $fields = []): void {
        if (!$this->_db->update('users_integrations', $this->data()->id, $fields)) {
            throw new RuntimeException('There was a problem updating integration user.');
        }
    }

    /**
     * Save a new user linked to a specific integration.
     *
     * @param User $user The user to link
     * @param string|null $identifier The id of the integration account
     * @param string|null $username The username of the integration account
     * @param bool $verified Verified the ownership of the integration account
     * @param string|null $code (optional) The verification code to verify the ownership
     */
    public function linkIntegration(User $user, ?string $identifier, ?string $username, bool $verified = false, string $code = null): void {
        $this->_db->createQuery(
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

        EventHandler::executeEvent('linkIntegrationUser', [
            'integration' => $this->_integration->getName(),
            'user_id' => $user->data()->id,
            'username' => $user->getDisplayname(),
            'content' => str_replace(['{user}', '{integration}'], [$user->getDisplayname(), $this->_integration->getName()], $this->_integration->getLanguage()->get('user', 'user_has_linked_integration')),
            'avatar_url' => $user->getAvatar(128, true),
            'url' => Util::getSelfURL() . ltrim($user->getProfileURL(), '/'),
            'integration_user' => [
                'identifier' => Output::getClean($identifier),
                'username' => Output::getClean($username),
                'verified' => (bool) $verified,
            ]
        ]);
    }

    /**
     * Verify user integration
     */
    public function verifyIntegration(): void {
        $this->update([
            'verified' => 1,
            'code' => null
        ]);
        
        $this->_integration->onSuccessfulVerification($this);

        $user = $this->getUser();
        EventHandler::executeEvent('verifyIntegrationUser', [
            'integration' => $this->_integration->getName(),
            'user_id' => $user->data()->id,
            'username' => $user->getDisplayname(),
            'content' => str_replace(['{user}', '{integration}'], [$user->getDisplayname(), $this->_integration->getName()], $this->_integration->getLanguage()->get('user', 'user_has_verified_integration')),
            'avatar_url' => $user->getAvatar(128, true),
            'url' => Util::getSelfURL() . ltrim($user->getProfileURL(), '/'),
            'integration_user' => [
                'identifier' => Output::getClean($this->data()->identifier),
                'username' => Output::getClean($this->data()->username),
                'verified' => (bool) $this->data()->verified,
            ]
        ]);
    }

    /**
     * Delete integration user data.
     */
    public function unlinkIntegration(): void {
        $this->_db->createQuery(
            'DELETE FROM nl2_users_integrations WHERE user_id = ? AND integration_id = ?', [
                $this->data()->user_id,
                $this->_integration->data()->id
            ]
        );

        $user = $this->getUser();
        EventHandler::executeEvent('unlinkIntegrationUser', [
            'integration' => $this->_integration->getName(),
            'user_id' => $user->data()->id,
            'username' => $user->getDisplayname(),
            'content' => str_replace(['{user}', '{integration}'], [$user->getDisplayname(), $this->_integration->getName()], $this->_integration->getLanguage()->get('user', 'user_has_unlinked_integration')),
            'avatar_url' => $user->getAvatar(128, true),
            'url' => Util::getSelfURL() . ltrim($user->getProfileURL(), '/'),
            'integration_user' => [
                'identifier' => Output::getClean($this->data()->identifier),
                'username' => Output::getClean($this->data()->username),
                'verified' => (bool) $this->data()->verified,
            ]
        ]);
    }
}