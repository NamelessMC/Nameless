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
        $this->_method = 'POST';
    }

    public function execute(Nameless2API $api) {
        $roles = array();

        if ($_POST['roles'] != null) {
            $roles = $_POST['roles'];
        }

        try {
            Discord::saveRoles($roles);
        } catch (Exception $e) {
            $api->throwError(33, $api->getLanguage()->get('api', 'unable_to_update_discord_roles'), $e->getMessage());
        }

        $api->returnArray(array('message' => $api->getLanguage()->get('api', 'discord_settings_updated')));
    }
}
