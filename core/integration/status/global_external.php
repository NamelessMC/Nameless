<?php
	// External query
	// Check cache
	$cache->setCache('query_cache');
	
	if(!$cache->isCached('query')){
		// Not cached, query the server
		// Use cURL
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0); 
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		curl_setopt($ch, CURLOPT_URL, 'https://mcapi.us/server/status?ip=' . $default_ip . '&port=' . $default_port);
		
		// Execute
		$ret = curl_exec($ch);

		// Store in cache
		$cache->store('query', json_decode($ret, true), 120);
		
		// Format the query
		$ret = json_decode($ret, true);
		
		$Info = array(
			'players' => array(
				'online' => $ret['players']['now'],
				'max' => $ret['players']['max']
			)
		);
		
	} else {
		// Cached, don't query
		$query = $cache->retrieve('query');
		
		$Info = array(
			'players' => array(
				'online' => $query['players']['now'],
				'max' => $query['players']['max']
			)
		);
	}
?>
