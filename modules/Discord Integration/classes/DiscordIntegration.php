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
        
        Session::flash('connections_success', str_replace('{x}', Output::getClean($this->_name), $this->_language->get('user', 'integration_unlinked')));
    }

    public function onSuccessfulVerification(IntegrationUser $integrationUser) {
        // attempt to update their Discord roles
        $user = $integrationUser->getUser();
        
        Discord::updateDiscordRoles($user, $user->getAllGroupIds(), []);
    }
    
    public function onRegistrationPageLoad(Fields $fields) {
    }

    public function beforeRegistrationValidation(Validate $validate) {
    }

    public function afterRegistrationValidation() {
    }

    public function successfulRegistration(User $user) {
    }
}