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
    private IntegrationUserData $_data;
    private User $_user;
    private IntegrationBase $_integration;

    public function __construct(IntegrationBase $integration, string $value = null, string $field = 'id', $query_data = null) {
        $this->_db = DB::getInstance();
        $this->_integration = $integration;

        if (!$query_data && $value) {
            $field = preg_replace('/[^A-Za-z_]+/', '', $field);

            $data = $this->_db->query("SELECT * FROM nl2_users_integrations WHERE $field = ? AND integration_id = ?", [$value, $integration->data()->id]);
            if ($data->count()) {
                $this->_data = new IntegrationUserData($data->first());
            }
        } else if ($query_data) {
            // Load data from existing query.
            $this->_data = new IntegrationUserData($query_data);
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
        return $this->_user ??= new User($this->data()->user_id);
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
        $this->_db->query(
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
        $this->_data = new IntegrationUserData($this->_db->query('SELECT * FROM nl2_users_integrations WHERE id = ?', [$this->_db->lastId()])->first());

        EventHandler::executeEvent(new UserIntegrationLinkedEvent(
            $this,
        ));
    }

    /**
     * Verify user integration
     */
    public function verifyIntegration(): void {
        $this->update([
            'verified' => true,
            'code' => null
        ]);

        $this->_integration->onSuccessfulVerification($this);

        EventHandler::executeEvent(new UserIntegrationVerifiedEvent(
            $this,
        ));
    }

    /**
     * Delete integration user data.
     */
    public function unlinkIntegration(): void {
        $this->_db->query(
            'DELETE FROM nl2_users_integrations WHERE user_id = ? AND integration_id = ?', [
                $this->data()->user_id,
                $this->_integration->data()->id
            ]
        );

        EventHandler::executeEvent(new UserIntegrationUnlinkedEvent(
            $this,
        ));
    }
}
