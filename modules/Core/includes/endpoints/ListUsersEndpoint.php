<?php
/**
 * No params
 *
 * @return string JSON Array
 */
class ListUsersEndpoint extends EndpointBase {
    public function __construct() {
        $this->_route = 'listUsers';
        $this->_module = 'Core';
        $this->_description = 'List all users on the NamelessMC site';
        $this->_method = 'GET';
    }

    public function execute(Nameless2API $api) {
        $discord_enabled = Util::isModuleEnabled('Discord Integration');

        if ($discord_enabled) {
            $query = 'SELECT id, username, uuid, isbanned AS banned, discord_id, active FROM nl2_users';
        } else {
            $query = 'SELECT id, username, uuid, isbanned AS banned, active FROM nl2_users';
        }

        if (isset($_GET['banned'])) {
            $query .= ' WHERE `isbanned` = ' . ($_GET['banned'] == 'true' ? '1' : '0');
            $filterBanned = true;
        }

        if (isset($_GET['active'])) {
            if (isset($filterBanned)) {
                $query .= ' AND';
            } else {
                $query .= ' WHERE';
            }
            $query .= ' `active` = ' . ($_GET['active'] == 'true' ? '1' : '0');
            $filterActive = true;
        }

        if ($discord_enabled && isset($_GET['discord_linked'])) {
            if (isset($filterBanned) || isset($filterActive)) {
                $query .= ' AND';
            } else {
                $query .= ' WHERE';
            }
            $query .= ' `discord_id` IS ' . ($_GET['discord_linked'] == 'true' ? 'NOT' : '') . ' NULL';
        }

        $users = $api->getDb()->query($query)->results();

        $users_json = array();
        foreach ($users as $user) {
            $user_json = [
                'id' => intval($user->id),
                'username' => $user->username,
                'uuid' => $user->uuid,
                'banned' => (bool) $user->banned,
                'verified' => (bool) $user->active,
            ];

            if ($discord_enabled) {
                $user_json['discord_id'] = intval($user->discord_id);
            }

            $users_json[] = $user_json;
        }

        $api->returnArray(array('users' => $users_json));
    }
}
