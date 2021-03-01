<?php

/**
 * @param int $id NamelessMC ID of group to view
 * @param string $name The NamelessMC NAME of the group to view
 *
 * @return string JSON Array
 */
class GroupInfoEndpoint extends EndpointBase {

    public function __construct() {
        $this->_route = 'groupInfo';
        $this->_module = 'Core';
        $this->_description = 'Lists groups and provides group information';
        $this->_method = 'GET';
    }

    public function execute(Nameless2API $api) {
        $query = 'SELECT id, name, staff, `order` FROM nl2_groups';
        $where = '';
        $order = ' ORDER BY `order`';
        $params = array();

        if (isset($_GET['id'])) {
            $where .= ' WHERE id = 0 ';
            if (is_array($_GET['id'])) {
                foreach ($_GET['id'] as $value) {
                    $where .= 'OR id = ? ';
                    $params[] = $value;
                }
            } else {
                $where .= 'OR id = ?';
                $params = array($_GET['id']);
            }
        } else if (isset($_GET['name'])) {
            $where .= ' WHERE name = null ';
            if (is_array($_GET['name'])) {
                foreach ($_GET['name'] as $value) {
                    $where .= 'OR name = ? ';
                    $params[] = $value;
                }
            } else {
                $where .= 'OR name = ?';
                $params = array($_GET['name']);
            }
        }

        $groups = $api->getDb()->query($query . $where . $order, $params)->results();

        $groups_array = array();
        foreach ($groups as $group) {
            $groups_array[] = array(
                'id' => intval($group->id),
                'name' => $group->name,
                'staff' => (bool) $group->staff,
                'order' => intval($group->order),
                'ingame_rank_name' => Util::getIngameRankName($group->id),
                'discord_role_id' => intval(Discord::getDiscordRoleId($api->getDb(), $group->id))
            );
        }

        $api->returnArray(array('groups' => $groups_array));
    }
}
