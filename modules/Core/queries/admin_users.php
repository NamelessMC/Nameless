<?php
// Returns set of users for the StaffCP Users tab
header('Content-type: application/json;charset=utf-8');

if (!$user->isLoggedIn() || !$user->hasPermission('admincp.users')) {
	die(json_encode('Unauthenticated'));
}

$sortColumns = ['username' => 'username', 'nickname' => 'nickname', 'group_order' => 'groupName', 'joined' => 'joined'];

$db = DB::getInstance();

$total = $db->query('SELECT COUNT(*) as `total` FROM nl2_users', array())->first()->total;
//$query = 'SELECT nl2_users.id as id, nl2_users.username as username, nl2_users.nickname as nickname, nl2_users.group_id as group_id, nl2_users.joined as joined, nl2_groups.order as group_order FROM nl2_users LEFT JOIN nl2_groups ON nl2_users.group_id = nl2_groups.id';
$query = 'SELECT U.id, U.username, U.nickname, U.joined, G.id as group_id, G.name as group_name, G.order as group_order FROM nl2_users AS U JOIN nl2_users_groups AS UG ON (U.id = UG.user_id) JOIN nl2_groups AS G ON (UG.group_id = G.id)';
$where = ' WHERE G.order = (SELECT min(iG.`order`) FROM nl2_users_groups AS iUG JOIN nl2_groups AS iG ON (iUG.group_id = iG.id) WHERE iUG.user_id = U.id GROUP BY iUG.user_id)';
$order = '';
$params = array();

if (isset($_GET['search']) && $_GET['search']['value'] != '') {
	$where = ' WHERE G.order = (SELECT min(iG.`order`) FROM nl2_users_groups AS iUG JOIN nl2_groups AS iG ON (iUG.group_id = iG.id) WHERE iUG.user_id = U.id GROUP BY iUG.user_id) AND  username LIKE ? OR G.order = (SELECT min(iG.`order`) FROM nl2_users_groups AS iUG JOIN nl2_groups AS iG ON (iUG.group_id = iG.id) WHERE iUG.user_id = U.id GROUP BY iUG.user_id) AND nickname LIKE ?';
	array_push($params, '%' . $_GET['search']['value'] . '%', '%' . $_GET['search']['value'] . '%');
}

if (isset($_GET['order']) && count($_GET['order'])) {
	$orderBy = array();

	for ($i = 0, $j = count($_GET['order']); $i < $j; $i++) {
		$column = intval($_GET['order'][$i]['column']);
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
	$limit .= ' LIMIT ' . intval($_GET['start']) . ', ' . intval($_GET['length']);
} else {
	// default 10
	$limit .= ' LIMIT 10';
}

if (strlen($where) > 0) {
	if (isset($_GET['search']) && $_GET['search']['value'] != '') {
		//$totalFiltered = $db->query('SELECT COUNT(*) as `total` FROM nl2_users WHERE username LIKE ? OR nickname LIKE ?', $params)->first()->total;
	} else {
		$totalFiltered = $db->query('SELECT COUNT(*) as `total` FROM nl2_users', $params)->first()->total;
	}
}

$results = $db->query($query . $where . $order . $limit, $params)->results();
$data = array();
$groups = array();

if (count($results)) {
	foreach ($results as $result) {
		$obj = new stdClass();
		$obj->id = $result->id;
		$obj->username = Output::getClean($result->username);
		$obj->nickname = Output::getClean($result->nickname);
		$obj->group = $result->group_id;
		$obj->groupName = Output::getClean($result->group_name);
		$obj->joined = date('d M Y', $result->joined);

		$data[] = $obj;
	}
}

echo json_encode(
	array(
		'draw' => isset($_GET['draw']) ? intval($_GET['draw']) : 0,
		'recordsTotal' => $total,
		'recordsFiltered' => isset($totalFiltered) ? $totalFiltered : $total,
		'data' => $data
	),
	JSON_PRETTY_PRINT
);
