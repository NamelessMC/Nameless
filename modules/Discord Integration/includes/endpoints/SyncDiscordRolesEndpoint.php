<?php

class SyncDiscordRolesEndpoint extends KeyAuthEndpoint {

    public function __construct() {
        $this->_route = 'discord/{user}/sync-roles';
        $this->_module = 'Discord Integration';
        $this->_description = 'Set a NamelessMC user\'s according to the supplied Discord Role ID list';
        $this->_method = 'POST';
    }

    public function execute(Nameless2API $api, User $user): void {
        $api->validateParams($_POST, []);

        if (!Discord::isBotSetup()) {
            $api->throwError(DiscordApiErrors::ERROR_DISCORD_INTEGRATION_DISABLED);
        }

        $log_array = GroupSyncManager::getInstance()->broadcastGroupChange(
            $user,
            DiscordGroupSyncInjector::class,
            $_POST['add'] ?? [],
            $_POST['remove'] ?? []
        );

        if (count($log_array)) {
            Log::getInstance()->log(Log::Action('discord/role_set'), json_encode($log_array), $user->data()->id);
        }

        $api->returnArray(array_merge(['message' => Discord::getLanguageTerm('group_updated')], $log_array));
    }
}
