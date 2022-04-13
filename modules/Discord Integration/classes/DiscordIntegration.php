<?php
/**
 * DiscordIntegration class
 *
 * @package Modules\Core\Integrations
 * @author Partydragen
 * @version 2.0.0-pr13
 * @license MIT
 */
class DiscordIntegration extends IntegrationBase {

    protected Language $_language;

    public function __construct(Language $language) {
        $this->_name = 'Discord';
        $this->_icon = 'fab fa-discord';
        $this->_language = $language;

        parent::__construct();
    }

    public function onLinkRequest(User $user) {
        $token = uniqid('', true);

        $integrationUser = new IntegrationUser($this);
        $integrationUser->linkIntegration($user, null, null, false, $token);

        Session::flash('connections_success', str_replace('{token}', $token, Discord::getLanguageTerm('discord_id_confirm')));
    }
    
    public function onVerifyRequest(User $user) {
        $token = uniqid('', true);

        $integrationUser = new IntegrationUser($this, $user->data()->id, 'user_id');
        $integrationUser->update([
            'code' => $token
        ]);

        Session::flash('connections_success', str_replace('{token}', $token, Discord::getLanguageTerm('discord_id_confirm')));
    }

    public function onUnlinkRequest(User $user) {
        $integrationUser = new IntegrationUser($this, $user->data()->id, 'user_id');
        $integrationUser->unlinkIntegration();
        
        Session::flash('connections_success', str_replace('{integration}', Output::getClean($this->_name), $this->_language->get('user', 'integration_unlinked')));
    }

    public function onSuccessfulVerification(IntegrationUser $integrationUser) {
        // attempt to update their Discord roles
        $user = $integrationUser->getUser();
        
        Discord::updateDiscordRoles($user, $user->getAllGroupIds(), []);
    }
    
    public function validateUsername(string $username, int $integration_user_id = 0): bool {
        $validation = Validate::check(['username' => $username], [
            'username' => [
                Validate::REQUIRED => true,
                Validate::REGEX => '/^.{2,32}#[0-9]{4}$/'
            ]
        ])->messages([
            'username' => [
                Validate::REQUIRED => str_replace('{integration}', $this->getName(), $this->_language->get('admin', 'integration_username_required')),
                Validate::REGEX => str_replace('{integration}', $this->getName(), $this->_language->get('admin', 'integration_username_invalid'))
            ]
        ]);

        if (count($validation->errors())) {
            // Validation errors
            foreach ($validation->errors() as $error) {
                $this->addError($error);
            }
        } else {
            // Ensure identifier doesn't already exist
            $exists = DB::getInstance()->selectQuery("SELECT * FROM nl2_users_integrations WHERE integration_id = ? AND username = ? AND id <> ?", [$this->data()->id, $username, $integration_user_id]);
            if ($exists->count()) {
                $this->addError(str_replace('{integration}', $this->getName(), $this->_language->get('user', 'integration_username_already_linked')));
                return false;
            }
        }

        return $validation->passed();
    }

    public function validateIdentifier(string $identifier, int $integration_user_id = 0): bool {
        $validation = Validate::check(['identifier' => $identifier], [
            'identifier' => [
                Validate::REQUIRED => true,
                Validate::NUMERIC => true,
                Validate::MIN => 17,
                Validate::MAX => 18
            ]
        ])->messages([
            'identifier' => [
                Validate::REQUIRED => str_replace('{integration}', $this->getName(), $this->_language->get('admin', 'integration_identifier_required')),
                Validate::NUMERIC => str_replace('{integration}', $this->getName(), $this->_language->get('admin', 'integration_identifier_invalid')),
                Validate::MIN => str_replace('{integration}', $this->getName(), $this->_language->get('admin', 'integration_identifier_invalid')),
                Validate::MAX => str_replace('{integration}', $this->getName(), $this->_language->get('admin', 'integration_identifier_invalid'))
            ]
        ]);

        if (count($validation->errors())) {
            // Validation errors
            foreach ($validation->errors() as $error) {
                $this->addError($error);
            }
        } else {
            // Ensure identifier doesn't already exist
            $exists = DB::getInstance()->selectQuery("SELECT * FROM nl2_users_integrations WHERE integration_id = ? AND identifier = ? AND id <> ?", [$this->data()->id, $identifier, $integration_user_id]);
            if ($exists->count()) {
                $this->addError(str_replace('{integration}', $this->getName(), $this->_language->get('user', 'integration_identifier_already_linked')));
                return false;
            }
        }

        return $validation->passed();
    }

    public function onRegistrationPageLoad(Fields $fields) {
        // Nothing to do here
    }

    public function beforeRegistrationValidation(Validate $validate) {
        // Nothing to do here
    }

    public function afterRegistrationValidation() {
        // Nothing to do here
    }

    public function successfulRegistration(User $user) {
        // Nothing to do here
    }
}