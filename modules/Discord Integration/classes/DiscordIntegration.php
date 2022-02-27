<?php

/*
 *	Made by Partydragen
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr13
 *
 *  License: MIT
 *
 *  Minecraft Integration
 */

class DiscordIntegration extends IntegrationBase {

    private Language $_language;

    public function __construct(Language $language) {
        $this->_name = 'Discord';
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
}