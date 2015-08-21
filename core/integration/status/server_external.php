<?php
// External query
// Check cache

$cache->setCache($server->name . 'query_cache');

if(!$cache->isCached('query')){
	// Not cached, query the server
	// Use cURL
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0); 
	curl_setopt($ch, CURLOPT_TIMEOUT, 5);
	curl_setopt($ch, CURLOPT_URL, 'https://mcapi.us/server/status?ip=' . $server_ip . '&port=' . $server_port);
	
	// Execute
	$ret = curl_exec($ch);

	// Store in cache
	$cache->store('query', json_decode($ret, true), 120);
	
	// Format the query
	$ret = json_decode($ret, true);
	
	if($ret['online'] != 1){
		$server_status_string .= '<span class="label label-danger">' . $general_language['offline'] . '</span>';
	} else {
		$Info = array(
			'players' => array(
				'online' => $ret['players']['now'],
				'max' => $ret['players']['max']
			)
		);
		$server_status_string .= '<span class="label label-success">' . $general_language['online'] . '</span> <strong>' . $Info['players']['online'] . '/' . $Info['players']['max'] . '</strong>';
	}
	
} else {
	// Cached, don't query
	$query = $cache->retrieve('query');
	
	if($query['online'] != 1){
		$server_status_string .= '<span class="label label-danger">' . $general_language['offline'] . '</span>';
	} else {
		$Info = array(
			'players' => array(
				'online' => $query['players']['now'],
				'max' => $query['players']['max']
			)
		);
		$server_status_string .= '<span class="label label-success">' . $general_language['online'] . '</span> <strong>' . $Info['players']['online'] . '/' . $Info['players']['max'] . '</strong>';
	}
}