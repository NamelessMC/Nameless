<?php
// Returns set of queue tasks for the StaffCP Queue tab
header('Content-type: application/json;charset=utf-8');

if (!$user->isLoggedIn() || !$user->hasPermission('admincp.core.queue')) {
    die(json_encode('Unauthenticated'));
}

$sortColumns = ['name' => 'name', 'scheduled_for' => 'scheduled_for', 'status' => 'status', 'task' => 'task'];

$db = DB::getInstance();

$total = $db->query('SELECT COUNT(*) as `total` FROM nl2_queue', [])->first()->total;
$query = 'SELECT `id`, `name`, `scheduled_for`, `status`, `task` FROM nl2_queue';
$where = '';
$order = '';
$limit = '';
$params = [];

if (isset($_GET['search']) && $_GET['search']['value'] != '') {
    $where .= ' WHERE `name` LIKE ? OR `task` LIKE ?';
    array_push($params, '%' . $_GET['search']['value'] . '%', '%' . $_GET['search']['value'] . '%');
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
        $order .= ' ORDER BY scheduled_for DESC';
    }
} else {
    $order .= ' ORDER BY scheduled_for DESC';
}

if (isset($_GET['start']) && $_GET['length'] != -1) {
    $limit .= ' LIMIT ' . (int)$_GET['start'] . ', ' . (int)$_GET['length'];
} else {
    // default 10
    $limit .= ' LIMIT 10';
}

if (strlen($where) > 0) {
    $totalFiltered = $db->query('SELECT COUNT(*) as `total` FROM nl2_queue' . $where, $params)->first()->total;
}

$results = $db->query($query . $where . $order . $limit, $params)->results();
$data = [];
$groups = [];

if (count($results)) {
    foreach ($results as $result) {
        $obj = new stdClass();
        $obj->id = Output::getClean($result->id);
        $obj->name = Output::getClean($result->name);
        $obj->scheduled_for = date(DATE_FORMAT, $result->scheduled_for);
        $obj->status = $language->get('admin', 'queue_status_' . $result->status);
        $obj->task = Output::getClean($result->task);

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
