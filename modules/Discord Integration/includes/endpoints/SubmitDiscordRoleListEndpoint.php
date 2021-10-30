<?php

/**
 * @param string $roles An array of Discord Roles with their name and ID
 *
 * @return string JSON Array
 */
class SubmitDiscordRoleListEndpoint extends EndpointBase {

    public function __construct() {
        $this->_route = 'submitDiscordRoleList';
        $this->_module = 'Discord Integration';
        $this->_description = 'Update NamelessMC\'s list of your Discord guild\'s roles.';
        $this->_method = 'POST';
    }

    public function execute(Nameless2API $api) {
        $roles = [];

        if ($_POST['roles'] != null) {
            $roles = $_POST['roles'];
        }

        try {
            Discord::saveRoles($roles);
        } catch (Exception $e) {
            $api->throwError(33, Discord::getLanguageTerm('unable_to_update_discord_roles'), $e->getMessage());
        }

        $api->returnArray(['message' => Discord::getLanguageTerm('discord_settings_updated')]);
    }
}
