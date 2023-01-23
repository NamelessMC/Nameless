<?php

/**
 * @param int $id NamelessMC ID of group to view
 * @param string $name The NamelessMC NAME of the group to view
 *
 * @return string JSON Array
 */
class GroupInfoEndpoint extends KeyAuthEndpoint {

    public function __construct() {
        $this->_route = 'groups';
        $this->_module = 'Core';
        $this->_description = 'Lists groups and provides group information';
        $this->_method = 'GET';
    }

    public function execute(Nameless2API $api): void {
        $query = 'SELECT id, name, staff, `order` FROM nl2_groups';
        $where = '';
        $order = ' ORDER BY `order`';
        $params = [];

        if (isset($_GET['id'])) {
            $where .= ' WHERE id = 0 ';
            if (is_array($_GET['id'])) {
                foreach ($_GET['id'] as $value) {
                    $where .= 'OR id = ? ';
                    $params[] = $value;
                }
            } else {
                $where .= 'OR id = ?';
                $params = [$_GET['id']];
            }
        } else {
            if (isset($_GET['name'])) {
                $where .= ' WHERE name = null ';
                if (is_array($_GET['name'])) {
                    foreach ($_GET['name'] as $value) {
                        $where .= 'OR name = ? ';
                        $params[] = $value;
                    }
                } else {
                    $where .= 'OR name = ?';
                    $params = [$_GET['name']];
                }
            }
        }

        $groups = $api->getDb()->query($query . $where . $order, $params)->results();

        $groups_array = [];
        foreach ($groups as $group) {
            $group_array = [
                'id' => (int)$group->id,
                'name' => $group->name,
                'staff' => (bool)$group->staff,
                'order' => (int)$group->order,
            ];

            $groups_array[] = $group_array;
        }

        $api->returnArray(['groups' => $groups_array]);
    }
}
