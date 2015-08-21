<?php
// Created by Bobcat000 (https://github.com/xPaw/PHP-Minecraft-Query/issues/34)
// Modified by Samerton (https://github.com/samerton) for NamelessMC (https://github.com/samerton/NamelessMC)

function SRVResolver($addr) {
	$port = 25565;

	$result = dns_get_record('_minecraft._tcp.' . $addr, DNS_SRV);

	if (count($result) > 0) {
		if (array_key_exists('target',$result[0])) $addr = $result[0]['target'];
		if (array_key_exists('port',  $result[0])) $port = $result[0]['port'];
	}

	$result = dns_get_record($addr, DNS_A);

	if (count($result) > 0 && array_key_exists('ip',$result[0]))
		$ip = $result[0]['ip'];
	else
		$ip = '127.0.0.1';
	
	return $ip . ':' . $port;
}