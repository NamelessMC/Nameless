<?php
// Returns set of users for the StaffCP Users tab
header('Content-type: application/json;charset=utf-8');

if (!$user->isLoggedIn() || !$user->hasPermission('admincp.users')) {
    die(json_encode('Unauthenticated'));
}

$sortColumns = ['id' => 'id', 'username' => 'username', 'joined' => 'joined'];

$db = DB::getInstance();

$total = $db->query('SELECT COUNT(*) as `total` FROM nl2_users', [])->first()->total;
$query = 'SELECT u.id, u.username, u.nickname, u.joined, u.gravatar, u.email, u.has_avatar, u.avatar_updated, IFNULL(nl2_users_integrations.identifier, \'none\') as uuid FROM nl2_users u LEFT JOIN nl2_users_integrations ON user_id=u.id AND integration_id=1';
$where = '';
$order = '';
$limit = '';
$params = [];

if (isset($_GET['search']) && $_GET['search']['value'] != '') {
    $where .= ' WHERE u.username LIKE ? OR u.nickname LIKE ? OR u.email LIKE ?';
    array_push($params, '%' . $_GET['search']['value'] . '%', '%' . $_GET['search']['value'] . '%', '%' . $_GET['search']['value'] . '%');
}

if (isset($_GET['order']) && count($_GET['order'])) {
    $orderBy = [];

    for ($i = 0, $j = count($_GET['order']); $i < $j; $i++) {
        $column = (int)$_GET['order'][$i]['column'];
        $requestColumn = $_GET['columns'][$column];

        $column = array_search($requestColumn['data'], $sortColumns);

        if ($column) {
            $dir = $_GET['order'][$i]['dir'] === 'asc' ?
                'ASC' :
                'DESC';

            $orderBy[] = '`' . $column . '` ' . $dir;
        }
    }

    if (count($orderBy)) {
        $order .= ' ORDER BY ' . implode(', ', $orderBy);
    } else {
        $order .= ' ORDER BY username ASC';
    }
} else {
    $order .= ' ORDER BY username ASC';
}

if (isset($_GET['start']) && $_GET['length'] != -1) {
    $limit .= ' LIMIT ' . (int)$_GET['start'] . ', ' . (int)$_GET['length'];
} else {
    // default 10
    $limit .= ' LIMIT 10';
}

if (strlen($where) > 0) {
    $totalFiltered = $db->query('SELECT COUNT(*) as `total` FROM nl2_users u' . $where, $params)->first()->total;
}

$results = $db->query($query . $where . $order . $limit, $params)->results();
$data = [];
$groups = [];

if (count($results)) {
    foreach ($results as $result) {
        $img = AvatarSource::getAvatarFromUserData($result, true, 30, true);

        $obj = new stdClass();
        $obj->id = $result->id;
        $obj->username = "<img src='{$img}' style='padding-right: 5px; max-height: 30px;'>" . Output::getClean($result->username) . "</img>";
        $obj->joined = date(DATE_FORMAT, $result->joined);

        // Get group
        $group = DB::getInstance()->query('SELECT `name` FROM nl2_groups g JOIN nl2_users_groups ug ON g.id = ug.group_id WHERE ug.user_id = ? ORDER BY g.order LIMIT 1', [$result->id]);
        $obj->groupName = $group->first()->name;

        $data[] = $obj;
    }
}

echo json_encode(
    [
        'draw' => isset($_GET['draw']) ? (int)$_GET['draw'] : 0,
        'recordsTotal' => $total,
        'recordsFiltered' => $totalFiltered ?? $total,
        'data' => $data
    ],
    JSON_PRETTY_PRINT
);
