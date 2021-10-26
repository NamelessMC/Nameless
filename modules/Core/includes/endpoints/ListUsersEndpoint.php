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
        $params = [];

        $discord_enabled = Util::isModuleEnabled('Discord Integration');

        if ($discord_enabled) {
            $query = 'SELECT u.id, u.username, u.uuid, u.isbanned AS banned, u.discord_id, u.active FROM nl2_users u';
        } else {
            $query = 'SELECT u.id, u.username, u.uuid, u.isbanned AS banned, u.active FROM nl2_users u';
        }

        $operator = isset($_GET['operator']) && $_GET['operator'] == 'OR'
                        ? ' OR'
                        : ' AND';

        if (isset($_GET['group_id'])) {
            $query .= ' INNER JOIN nl2_users_groups ug ON u.id = ug.user_id WHERE ug.group_id = ?';
            $params[] = $_GET['group_id'];
            $filterGroup = true;
        }

        if (isset($_GET['banned'])) {
            if (isset($filterGroup)) {
                $query .= $operator;
            } else {
                $query .= ' WHERE';
            }
            $query .= '`u.isbanned` = ' . ($_GET['banned'] == 'true' ? '1' : '0');
            $filterBanned = true;
        }

        if (isset($_GET['active'])) {
            if (isset($filterBanned) || isset($filterGroup)) {
                $query .= $operator;
            } else {
                $query .= ' WHERE';
            }
            $query .= ' `u.active` = ' . ($_GET['active'] == 'true' ? '1' : '0');
            $filterActive = true;
        }

        if ($discord_enabled && isset($_GET['discord_linked'])) {
            if (isset($filterBanned) || isset($filterActive) || isset($filterGroup)) {
                $query .= $operator;
            } else {
                $query .= ' WHERE';
            }
            $query .= ' `u.discord_id` IS ' . ($_GET['discord_linked'] == 'true' ? 'NOT' : '') . ' NULL';
        }

        $users = $api->getDb()->selectQuery($query, $params)->results();

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
