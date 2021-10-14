<?php
// 2.0.0 pr-13

try {
	$queries->alterTable('mc_servers', 'rcon_port', 'int(11) DEFAULT NULL');
} catch (Exception $e) {
	echo $e->getMessage() . '<br />';
}
try {
	$queries->alterTable('mc_servers', 'rcon_pass', 'varchar(255) DEFAULT NULL');
} catch (Exception $e) {
	echo $e->getMessage() . '<br />';
}
try {
	$queries->alterTable('mc_servers', 'rcon_status', 'tinyint(1) DEFAULT NULL');
} catch (Exception $e) {
	echo $e->getMessage() . '<br />';
}
