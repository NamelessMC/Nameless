<?php

use GuzzleHttp\Exception\GuzzleException;

/**
 * TODO: Add description
 *
 * @package Modules\Discord Integration\Endpoints
 * @author UNKNOWN
 * @version UNKNOWN
 * @license MIT
 */
class SetDiscordRolesEndpoint extends KeyAuthEndpoint {

    public function __construct() {
        $this->_route = 'discord/set-roles';
        $this->_module = 'Discord Integration';
        $this->_description = 'Set a NamelessMC user\'s according to the supplied Discord Role ID list';
        $this->_method = 'POST';
    }

    /**
     * @param Nameless2API $api
     *
     * @return void
     * @throws GuzzleException
     */
    public function execute(Nameless2API $api): void {
        $api->validateParams($_POST, ['user']);

        if (!Discord::isBotSetup()) {
            $api->throwError(DiscordApiErrors::ERROR_DISCORD_INTEGRATION_DISABLED);
        }

        $user = $api->getUser('id', $_POST['user']);

        $log_array = GroupSyncManager::getInstance()->broadcastChange(
            $user,
            DiscordGroupSyncInjector::class,
            $_POST['roles'] ?? []
        );

        if (count($log_array)) {
            Log::getInstance()->log(Log::Action('discord/role_set'), json_encode($log_array), $user->data()->id);
        }

        $api->returnArray(array_merge(['message' => Discord::getLanguageTerm('group_updated')], $log_array));
    }
}
