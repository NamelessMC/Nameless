<?php 
/*
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 */

if(isset($play_enabled) && $play_enabled == '0'){
	// Page is disabled
	echo '<script data-cfasync="false">window.location.replace(\'/\');</script>';
	die();
}
 
// Index page
$page = 'play'; // for navbar
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Server status for the <?php echo $sitename; ?> community">
    <meta name="author" content="<?php echo $sitename; ?>">
	<?php if(isset($custom_meta)){ echo $custom_meta; } ?>
	
	<?php
	// Generate header and navbar content
	// Page title
	$title = $navbar_language['play'];
	
	require('core/includes/template/generate.php');
	?>
	
	<!-- Custom style -->
	<style>
	html {
		overflow-y: scroll;
	}
	</style>
	
  </head>
  <body>
	<?php
	// Load navbar
	$smarty->display('styles/templates/' . $template . '/navbar.tpl');
	
	// Assign page content to Smarty variables
	
	// Get the main IP
	$main_ip = $queries->getWhere('mc_servers', array('is_default', '=', 1));
	$pre17 	 = $main_ip[0]->pre;
	$query_ip = htmlspecialchars($main_ip[0]->query_ip);
	$main_ip = htmlspecialchars($main_ip[0]->ip);
	
	/*
	 *  Get port of Minecraft server
	 */
	$parts = explode(':', $query_ip);
	if(count($parts) == 1){
		$domain = $parts[0];
		$default_ip = $parts[0];
		$default_port = 25565;
	} else if(count($parts) == 2){
		$domain = $parts[0];
		$default_ip = $parts[0];
		$default_port = $parts[1];
		$port = $parts[1];
	} else {
		echo 'Invalid Query IP';
		die();
	}

	// Get IP to display
	$parts = explode(':', $main_ip);
	if(count($parts) == 1){
		$display_domain = $parts[0];
	} else if(count($parts) == 2){
		$display_domain = $parts[0];
		$display_port = $parts[1];
	} else {
		echo 'Invalid Display IP';
		die();
	}

	if((!isset($display_port)) || ($display_port == "25565")){
		$address = $display_domain;
	} else {
		$address = $display_domain . ':' . $port;
	}

	$connect_with = str_replace('{x}', htmlspecialchars($address), $general_language['connect_with']);
	$smarty->assign('CONNECT_WITH', $connect_with);
	
	// Query the main IP
	// Are we using the built-in query or an external API?
	$external_query = $queries->getWhere('settings', array('name', '=', 'external_query'));
	$external_query = $external_query[0]->value;
	
	if($external_query == 'false'){
		// Built in query, continue as normal
	    require('core/integration/status/global.php'); 
	} else {
		// External query
		$cache = new Cache();
		require('core/integration/status/global_external.php');
	}
	
	if(empty($Info)){
		// Unable to query, offline
		$smarty->assign('MAIN_ONLINE', 0);
	} else {
		// Able to query, online
		$smarty->assign('MAIN_ONLINE', 1);
	}
	
	// Player count
    if($pre17 == 0){
		if(empty($Info['players']['max'])){
			$player_count = $Info['players']['online'];
		} else {
			$player_count = $Info['players']['online'] . ' / ' . $Info['players']['max'];
		}
	} else {
		if(empty($Info['MaxPlayers'])){
			$player_count = $Info['Players'];
		} else {
			$player_count = $Info['Players'] . ' / ' . $Info['MaxPlayers'];
		}
	}
	$smarty->assign('PLAYER_COUNT', htmlspecialchars($player_count));
	
	// Assign timer to variable
	if(isset($Timer)){
		$smarty->assign('TIMER', $Timer . $time_language['seconds_short']);
	} else {
		$smarty->assign('TIMER', 'n/a');
	}
	
	if($external_query == 'false'){
		$smarty->assign('PLAY_TITLE', str_replace(':', '', $general_language['players_online']));
	} else {
		$smarty->assign('PLAY_TITLE', $general_language['server_status']);
	}
	
	// Loop through defined servers and query them
	$server_status_string = '';
	$servers = $queries->getWhere('mc_servers', array('display', '=', 1));
	
	if($external_query == 'false'){
		// Built in query, continue as normal
		require('core/integration/status/server.php'); 
		$serverStatus = new ServerStatus();
	}
	
	foreach($servers as $server){
		$pre17 = $server->pre;
		
		$parts = explode(':', $server->query_ip);
		if(count($parts) == 1){
			$domain = $parts[0];
			$server_ip = $parts[0];
			$server_port = 25565;
		} else if(count($parts) == 2){
			$server_ip = $parts[0];
			$server_port = $parts[1];
		} else {
			echo 'Invalid Query IP';
			die();
		}
		
		// Query servers
		$server_status_string .= '<h4>' . htmlspecialchars($server->name) . '</h4>';
		if($external_query == 'false'){
			$server_status_string .= $serverStatus->serverPlay($server_ip, $server_port, $server->name, $pre17, $general_language);
		} else {
			require('core/integration/status/server_external.php');
		}
		$server_status_string .= '<hr>';
	}
	
	$smarty->assign('SERVER_STATUS', $server_status_string);
	
	// Language variables
	$smarty->assign('STATUS', $general_language['status']);
	$smarty->assign('ONLINE', $general_language['online']);
	$smarty->assign('OFFLINE', $general_language['offline']);
	$smarty->assign('PLAYERS_ONLINE', $general_language['players_online']);
	$smarty->assign('QUERIED_IN', $general_language['queried_in']);
	
	$smarty->display('styles/templates/' . $template . '/play.tpl');

	// Footer
	require('core/includes/template/footer.php');
	$smarty->display('styles/templates/' . $template . '/footer.tpl');
	
	// Scripts 
	require('core/includes/template/scripts.php');
	?>
  </body>
</html>
