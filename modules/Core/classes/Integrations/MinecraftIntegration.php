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

class MinecraftIntegration extends IntegrationBase {

    private Language $_language;

    public function __construct(Language $language) {
        $this->_name = 'Minecraft';
        $this->_language = $language;

        parent::__construct();
    }

    public function onLink(User $user) {
        require(ROOT_PATH . '/core/integration/uuid.php'); // For UUID stuff
        
        $queries = new Queries();
        $username = $user->data()->username;
        
        $uuid_linking = $queries->getWhere('settings', ['name', '=', 'uuid_linking']);
        $uuid_linking = $uuid_linking[0]->value;
        
        if ($uuid_linking == 1) {
            // Perform validation on Minecraft name
            $profile = ProfileUtils::getProfile(str_replace(' ', '%20', $username));

            $mcname_result = $profile ? $profile->getProfileAsArray() : [];

            if (isset($mcname_result['username'], $mcname_result['uuid']) && !empty($mcname_result['username']) && !empty($mcname_result['uuid'])) {
                // Valid
                $uuid = Output::getClean($mcname_result['uuid']);

                // Ensure UUID is unique
                $uuid_query = $queries->getWhere('users', ['uuid', '=', $uuid]);
                if (count($uuid_query)) {
                    $uuid_error = $this->_language->get('user', 'uuid_already_exists');
                }
                
                $integrationUser = new IntegrationUser($this, $uuid, 'identifier');
                if($integrationUser->exists()) {
                    Session::flash('connections_error', $this->_language->get('user', 'uuid_already_exists'));
                    return;
                }
                
                $integrationUser = new IntegrationUser($this, $username, 'username');
                if($integrationUser->exists()) {
                    Session::flash('connections_error', $this->_language->get('user', 'username_mcname_email_exists'));
                    return;
                }

            } else {
                // Invalid
                Session::flash('connections_error', $this->_language->get('user', 'invalid_mcname'));
                return;
            }
        } else {
            $uuid = '';
            
            $integrationUser = new IntegrationUser($this, $username, 'username');
            if($integrationUser->exists()) {
                Session::flash('connections_error', $this->_language->get('user', 'username_mcname_email_exists'));
                return;
            }
        }
        
        $code = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 10);
        
        $integrationUser = new IntegrationUser($this);
        $integrationUser->linkIntegration($user, $uuid, $username, false, $code);
        
        // API verification
        $api_verification = $queries->getWhere('settings', ['name', '=', 'api_verification']);
        $api_verification = $api_verification[0]->value;
        
        if ($api_verification == '1') {
            Session::flash('connections_success', str_replace('{x}', Output::getClean($code), $this->_language->get('user', 'validate_account_command')));
        }
    }

    public function onUnlink(User $user) {
        $integrationUser = new IntegrationUser($this, $user->data()->id, 'user_id');
        $integrationUser->unlinkIntegration();
        
        Session::flash('connections_success', str_replace('{x}', Output::getClean($this->_name), $this->_language->get('user', 'connection_unlinked')));
    }
}