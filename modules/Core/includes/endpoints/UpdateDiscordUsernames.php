<?php
/**
 * @param string $user JSON Array of user ID -> Discord username to update
 * 
 * @return string JSON Array
 */
class UpdateDiscordUsernames extends EndpointBase
{

    public function __construct()
    {
        $this->_route = 'updateDiscordUsernames';
        $this->_module = 'Core';
        $this->_description = 'Bulk update many user\'s Discord usernames to display on their settings page.';
    }

    public function execute(Nameless2API $api)
    {
        if ($api->isValidated()) {
            if ($api->validateParams($_POST, ['users'])) {
                foreach ($_POST['users'] as $row) {
                    $user = $api->getUser('id', Output::getClean($row->id));
                    $discord_username = Output::getClean($row->name);
                    try {
                        $api->getDb()->update('users', $user->data()->id, array('discord_username' => $discord_username));
                    } catch (Exception $e) {
                        $api->throwError(29, $api->getLanguage()->get('api', 'unable_to_update_discord_username'));
                    }
                }
                
                $api->returnArray(array('message' => $api->getLanguage()->get('api', 'discord_usernames_updated')));
            }
        }
    }
}
