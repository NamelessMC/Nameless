<?php

/**
 * @param string $roles An array of Discord Roles with their name and ID
 *
 * @return string JSON Array
 */
class SubmitDiscordRoleListEndpoint extends EndpointBase {

    public function __construct() {
        $this->_route = 'submitDiscordRoleList';
        $this->_module = 'Core';
        $this->_description = 'Update NamelessMC\'s list of your Discord guild\'s roles.';
    }

    public function execute(Nameless2API $api) {
        if ($api->isValidated()) {

            $roles = array();

            if ($_POST['roles'] != null) {
                $roles = array(json_encode($_POST['roles']));
            }

            try {
                $api->getDb()->createQuery('UPDATE nl2_settings SET `value` = ? WHERE `name` = "discord_roles"', $roles);
            } catch (Exception $e) {
                $api->throwError(33, $api->getLanguage()->get('api', 'unable_to_update_discord_roles'));
            }

            $api->returnArray(array('message' => $api->getLanguage()->get('api', 'discord_settings_updated')));
        }
    }
}
