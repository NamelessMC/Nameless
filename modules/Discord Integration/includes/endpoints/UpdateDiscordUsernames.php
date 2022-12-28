<?php
declare(strict_types=1);

/**
 * @package Modules\Discord Integration
 * @author Unknown
 * @version 2.1.0
 * @license MIT
 */
class UpdateDiscordUsernames extends KeyAuthEndpoint {

    public function __construct() {
        $this->_route = 'discord/update-usernames';
        $this->_module = 'Discord Integration';
        $this->_description = 'Bulk update many user\'s Discord usernames to display on their settings page.';
        $this->_method = 'POST';
    }

    /**
     * @param Nameless2API $api
     *
     * @return void
     * @throws Exception
     */
    public function execute(Nameless2API $api): void {
        $api->validateParams($_POST, ['users']);

        try {
            $integration = Integrations::getInstance()->getIntegration('Discord');
            $updated = 0;
            foreach ($_POST['users'] as $row) {
                $integrationUser = new IntegrationUser($integration, $row['id'], 'identifier');
                if ($integrationUser->exists()) {
                    $discord_username = Output::getClean($row['name']);

                    if ($integrationUser->data()->username !== $discord_username) {
                        $integrationUser->update([
                            'username' => $discord_username
                        ]);

                        $updated++;
                    }
                }
            }
        } catch (Exception $e) {
            $api->throwError(DiscordApiErrors::ERROR_UNABLE_TO_SET_DISCORD_BOT_USERNAME, $e->getMessage(), 500);
        }

        $api->returnArray(['message' => Discord::getLanguageTerm('discord_usernames_updated'), 'updated_users' => $updated]);
    }
}
