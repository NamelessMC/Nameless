<?php

/**
 * No params
 *
 * @return string JSON Array
 */
class ListUsersEndpoint extends KeyAuthEndpoint {

    public function __construct() {
        $this->_route = 'users';
        $this->_module = 'Core';
        $this->_description = 'List all users on the NamelessMC site';
        $this->_method = 'GET';
    }

    public function execute(Nameless2API $api): void {
        $query = 'SELECT u.id, u.username, u.isbanned AS banned, u.active FROM nl2_users u';
        $where = [];
        $params = [];

        $operator = isset($_GET['operator']) && $_GET['operator'] == 'OR'
            ? ' OR '
            : ' AND ';

        if (isset($_GET['group_id'])) {
            $query .= ' INNER JOIN nl2_users_groups ug ON u.id = ug.user_id';
            $where[] = 'ug.group_id = ?';
            $params[] = $_GET['group_id'];
        }

        if (isset($_GET['integration'])) {
            $query .= ' INNER JOIN nl2_users_integrations ui ON ui.user_id=u.id INNER JOIN nl2_integrations i ON i.id=ui.integration_id';
            $where[] = 'i.name = ?';
            $params[] = $_GET['integration'];
        }

        if (isset($_GET['banned'])) {
            $where[] = '`u`.`isbanned` = ' . ($_GET['banned'] == 'true' ? '1' : '0');
        }

        if (isset($_GET['active'])) {
            $where[] = '`u`.`active` = ' . ($_GET['active'] == 'true' ? '1' : '0');
        }

        // Build where string
        if (!empty($where)) {
            $query .= ' WHERE ';
            foreach ($where as $item) {
                $query .= $item . $operator;
            }
            $query = rtrim($query, $operator);
        }

        $return_array = [
            'limit' => -1,
            'offset' => 0,
            'next_page' => null,
            'previous_page' => null,
        ];

        $base_url = URL::getSelfURL() . 'index.php?route=/api/v2/users&';
        if (isset($_GET['limit']) && is_numeric($_GET['limit'])) {
            $limit = (int) $_GET['limit'];
            if ($limit >= 1) {
                $query .= ' LIMIT ' . $limit;
                $return_array['limit'] = $limit;

                if (isset($_GET['offset']) && is_numeric($_GET['offset'])) {
                    $offset = (int) $_GET['offset'];
                    $query .= ' OFFSET ' . $offset;
                    $return_array['offset'] = $offset;
                    $return_array['next_page'] = $base_url . 'limit=' . $limit . '&offset=' . ($offset + $limit);
                    if ($offset - $limit >= 0) {
                        // Only show previous page if it would be a valid offset
                        $return_array['previous_page'] = $base_url . 'limit=' . $limit . '&offset=' . ($offset - $limit);
                    }
                }
            }
        } else {
            $query .= ' LIMIT 15';
            $return_array['limit'] = 15;
            $return_array['next_page'] = $base_url . 'limit=15&offset=' . ($return_array['offset'] + 15);
        }

        $users = $api->getDb()->query($query, $params)->results();

        $users_json = [];
        foreach ($users as $user) {
            $integrations = [];
            $integrations_query = $api->getDb()->query('SELECT ui.*, i.name FROM nl2_users_integrations ui INNER JOIN nl2_integrations i ON i.id=ui.integration_id WHERE user_id = ? AND username IS NOT NULL AND identifier IS NOT NULL', [$user->id])->results();
            foreach ($integrations_query as $integration) {
                $integrations[] = [
                    'integration' => Output::getClean($integration->name),
                    'identifier' => Output::getClean($integration->identifier),
                    'username' => Output::getClean($integration->username),
                    'verified' => (bool) $integration->verified,
                    'linked_date' => $integration->date,
                    'show_publicly' => (bool) $integration->show_publicly,
                ];
            }

            $user_json = [
                'id' => (int)$user->id,
                'username' => $user->username,
                'banned' => (bool)$user->banned,
                'verified' => (bool)$user->active,
                'integrations' => $integrations
            ];

            if (isset($_GET['groups'])) {
                $groups = $api->getDb()->query(
                    <<<SQL
                    SELECT g.id, g.name, g.staff, g.order
                    FROM nl2_users_groups ug
                        RIGHT JOIN nl2_groups g
                            ON g.id = ug.group_id
                    WHERE ug.user_id = ?
                    SQL,
                    [$user->id]
                );

                $user_json['groups'] = $groups->results();
            }

            $users_json[] = $user_json;
        }

        $return_array['users'] = $users_json;

        $api->returnArray($return_array);
    }
}
